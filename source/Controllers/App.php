<?php

namespace Source\Controllers;

use Source\Support\EasyCSV;
use Source\Core\Controller;
use Source\Models\Auth;
use Source\Models\Tarefa;
use Source\Models\Software;
use Source\Models\Mensagens;
use Source\Models\Account;


use Source\Core\Session;
use stdClass;

class App extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    /**
     * dashboard
     * 
     * @return void
     */
    public function home()
    {
        $imei = session()->user->imei;
        $objMensagens = new Mensagens();
        $total = new stdClass();
        $total->whats = $objMensagens->find('imei=:imei', 'imei=' . $imei, 'id')->count();

        $objMensagens = new Mensagens();
        $date1 = date('Y-m-d 00:00:00'); // data de hoje
        $date2 = date('Y-m-d 23:59:59'); // data de hoje
        $total->whatsToday = $objMensagens->find('imei=:imei and data BETWEEN :data1 and :data2', "imei={$imei}&data1={$date1}&data2=$date2", 'id')->count();

        $objMensagens = new Mensagens();
        $date1 = date('Y-m-d',strtotime("-7 days"))." 00:00:00"; 
        $date2 = date('Y-m-d  23:59:59'); 
         $total->sevenDays = $objMensagens->find('imei=:imei and data BETWEEN :data1 and :data2', "imei={$imei}&data1={$date1}&data2=$date2", 'id')->count();
        // var_dump($total->sevenDays);
        // exit;

        $objMensagens = new Mensagens();
        $whats = $objMensagens->find('imei=:imei', "imei={$imei}", 'tipo,`data` as periodo,nome_contato AS contato,mensagem')->order('id',true)->limit(5)->fetch(true);

        echo $this->view->render("home", array(
            "title" => label('dashboard'),
            'total' => $total,
            'account' => $this->user,
            'whats' =>$whats
        ));
    } 


    /**
     * Whatsapp
     *
     * @return void
     */
    public function whatsapp($data = false)
    {
        $result = new Mensagens();
        $imei = session()->user->imei;

        //AJAX: remover
        if (!empty($data['remove'])) {

            if (empty($data['id'])) {
                $json["message"] = $this->message->error(msg('reports_not_found'))->flash();
                $json["reload"] = true;
                echo json_encode($json);
                return;
            }

            $get = $result->findById($data['id'], 'id');

            if (!empty($get) && $get->destroy()) {
                $json["message"] = $this->message->success(msg('successfully_deleted'))->flash();
                $json["reload"] = true;
                echo json_encode($json);
                return;
            } else {
                $json["message"] = $this->message->error(msg('couldnt_delete'))->flash();
                $json["reload"] = true;
                echo json_encode($json);
                return;
            }
        }
 

        $terms = ' imei = :imei ';
        $terms .= (!empty($this->filter->data1) ? ' AND data >= :data1 ' : '');
        $terms .= (!empty($this->filter->data2) ? ' AND data <= :data2 ' : '');

        $params = "imei={$imei}";
        $params .= (!empty($this->filter->data1) ? '&data1=' . $this->filter->data1 . ' 00:00:00' : '');
        $params .=  (!empty($this->filter->data2) ? '&data2=' . $this->filter->data2 . ' 23:59:59' : '');

        $rows = $result->find($terms, $params, 'id')->count();
        $result = $result->find($terms, $params, 'tipo,`data` as periodo,nome_contato AS contato,mensagem')->order('date', true);


        // EXPORT CSV
        if (!empty($data['export']) && $data['export'] == 'csv') {
            $csv = new EasyCSV('Whatsapp');
            $titles = array(
                'periodo' => label('date'),
                'mensagem' => label('message'),
                'contato' => label('contact'),
                'tipo' => 'Status' 
            );
            $csv->setTitles($titles);
            $csv->setRows($result->fetch(true));
            $csv->generate();
            return; 
        }

        //Ativar filtro de data
        $this->filter->data = true;

        echo $this->view->render("whatsapp", array(
            "title" => 'Whatsapp',
            'result' => $result->pagination()->fetch(true),
            'rows' => $rows,
            'filter' => $this->filter
        ));
    }

    /**
     * Tarefa
     *
     * @param [type] $data
     * @return void
     */
    public function tarefa($data = null)
    {
        $imei = session()->user->imei;
        $objTarefa = new Tarefa();

        // AJAX: atualizando o status 
        if (!empty($data["update"])) {

            $tarefa = $objTarefa->find(
                "imei = :imei",
                "imei={$imei}"
            )->fetch();

            // se não encontrar resultado não pode continuar
            if (!$tarefa) {
                $json["reload"] = true;
                $json["message"] = $this->message->error(msg('ambient_not_found'))->flash();
                echo json_encode($json);
                return;
            }

            $tarefa->status =  (isset($tarefa->status) && $tarefa->status == 1 ? 0 : 1);
            if ($tarefa->save()) {
                //status ativadado
                if ($tarefa->status == 1) {
                    $json["reload"] = true;
                    $json["message"] = $this->message->success(msg('ambient_request_successfully'))->flash();
                } else {
                    //status desativado
                    $json["reload"] = true;
                    $json["message"] = $this->message->warning(msg('ambient_request_canceled'))->flash();
                }
            } else {
                //se houver erro no update mostrar essa mensagem
                $json["reload"] = true;
                $json["message"] = $this->message->error(msg('ambient_not_possible_request'))->flash();
            }

            //zerar sessão de tarefa!
            $session = new Session();
            $session->set("tarefa_tempo_exedido", 0);
            $session->set("tarefa_sequencia", 0);
            $session->clearSession("tarefa_tarefa_comunicacao_aparelho");

            echo json_encode($json);
            return;
        }

        // AJAX loop: verificar status 
        extract($_POST);
        if (!empty($ajax) && !empty($acao) && isset($seq)) {
            if ($acao == 'buscar') {
                $tarefa = $objTarefa->find(
                    "imei = :imei",
                    "imei={$imei}"
                )->fetch();

                $session = new Session();
                $session->set("tarefa_sequencia", $seq);



                if ($tarefa->status == 1 || $tarefa->status == 2) {
                    $limit = 5 * 60; // 5 minutos
                    // sequencia passou de limite de espera!
                    if (session()->tarefa_sequencia > $limit) {
                        $json["reload"] = true;
                        $json["message"] = $this->message->warning(msg('time_expired'))->flash();

                        //tarefa_tempo_exedido recebe 1 para informar que passou do tempo limit
                        $session->set("tarefa_tempo_exedido", 1);
                    } else {
                        $json["message"] = $this->message->success(msg('request_in_progress'))->render();
                        //zerar tarefa_tempo_exedido
                        $session->set("tarefa_tempo_exedido", 0);

                        //recarregar pagina só uma vez quando não existir a sessão 
                        if ($tarefa->status == 2 && empty(session()->tarefa_tarefa_comunicacao_aparelho)) {
                            $json["message"] = $this->message->success(msg('request_in_progress'))->flash();
                            $json["reload"] = true;
                            $session->set("tarefa_tarefa_comunicacao_aparelho", 1);
                        }

                        if ($tarefa->status < 2) {
                            $session->clearSession("tarefa_tarefa_comunicacao_aparelho");
                        }
                    }
                } else {
                    // redirecionara para pagina escuta ambiente
                    $json["redirect"] = url("audio/escuta-ambiente");

                    // zerar sessão tarefa
                    $session->set("tarefa_sequencia", 0);
                    $session->set("tarefa_tempo_exedido", 0);
                    $session->clearSession("tarefa_tarefa_comunicacao_aparelho");
                }

                echo json_encode($json);
                return;
            }
        }

        // buscar tarefa
        $tarefa = $objTarefa->find("imei = :imei", "imei={$imei}")->fetch();

        // se não existir nenhuma tarefa para esse imei, então criar nova tarefa
        if (!$tarefa) {
            $objTarefa->add($imei);
            $objTarefa->save();
            $id = $objTarefa->lastId();
            $status = 0;
        } else {
            // ja existe uma tarefa para esse imei
            $id = $tarefa->id;
            $status = $tarefa->status;
        }

        echo $this->view->render("tarefa", array(
            "title" => label('tasks'),
            'imei' => $imei,
            'id' => $id,
            'status' => $status
        ));
    }


    /**
     * Registro
     *
     * @param [type] $data
     * @return void
     */
    public function registro($data = null)
    {
        $licenca = session()->user->licenca;
        $dataVencimento = session()->user->vencimento;

        if (!empty($data["csrf"])) {
            $data = filter_array($data);

            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['licenca'])) {
                $json["message"] = $this->message->error(msg('insert_license'))->render();
                echo json_encode($json);
                return;
            }

            if ($data['licenca'] == $licenca) {
                $json["message"] = $this->message->info(msg('license_is_registered'))->render();
                echo json_encode($json);
                return;
            }

            $objSoftware = new Software();
            $software = $objSoftware->find("licenca = :licenca", "licenca={$data['licenca']}", 'validade')
                ->join('item', 'id_item', '', '', 'id_item', 'id_item')
                ->join('pedido', 'id_pedido', '', '', 'id_pedido', 'id_pedido', 'item')
                ->fetch(true);

            if (empty($software->id_item)) {
                $json["message"] = $this->message->error(msg('license_not_found'))->render();
                echo json_encode($json);
                return;
            }

            // rescreve objeto para remover os joins
            $objSoftware = new Software();
            $software = $objSoftware->find("licenca = :licenca AND id_item = :id_item", "licenca={$data['licenca']}&id_item=" . $software->id_item)->fetch();
            $software->uso = 1;
            if (!$software->save()) {
                $json["message"] = $software->message()->render();
                echo json_encode($json);
                return;
            }

            $objUser = new Account();
            $user = $objUser->find('imei = :imei', 'imei=' . session()->user->imei)->fetch();
            $user->licenca = $software->licenca;
            $user->vencimento = $software->validade;
            $user->uso_lic = 1;
            $user->status_lic = 1;

            if (!$user->save()) {
                $json["message"] = $software->message()->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success(msg('license_successfully'))->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }

        $diferenca = floor((strtotime($dataVencimento) - strtotime(date('Y-m-d'))) / (60 * 60 * 24));
        $dias = ($diferenca <= 0) ? 0 : $diferenca;

        $cadastrar = (!empty($data['uso']) && $data['uso'] == 1 ? true : false);
        $renovar = (!empty($data['vencido']) && $data['vencido'] == 1 ? true : false);
        echo $this->view->render("registro", array(
            "title" => label('record'),
            'licenca' => $licenca,
            'dias' => $dias,
            'diferenca' => $diferenca,
            'renovar' => $renovar,
            'cadastrar' => $cadastrar
        ));
    }


    /**
     * Alterar senha
     *
     * @param [type] $data
     * @return void
     */
    public function alterarSenha($data = null)
    {
        if (!empty($data["csrf"])) {
            $data = filter_array($data);

            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['senhaAtual'])) {
                $json["message"] = $this->message->error(msg('current_pass'))->render();
                echo json_encode($json);
                return;
            }

            $senhaAtual = base64_decode(session()->user->senha);

            if ($data['senhaAtual'] != $senhaAtual) {
                $json["message"] = $this->message->error(msg('pass_invalid'))->render();
                echo json_encode($json);
                return;
            }

            $data['senha'] = trim($data['senha']);
            if (empty($data['senha'])) {
                $json["message"] = $this->message->error(msg('password_insert'))->render();
                echo json_encode($json);
                return;
            }

            if (strlen($data['senha']) < 6) {
                $json["message"] = $this->message->error(msg('password_6_characters.'))->render();
                echo json_encode($json);
                return;
            }

            if (empty($data['confirmacao'])) {
                $json["message"] = $this->message->error(msg('confirm_new_password'))->render();
                echo json_encode($json);
                return;
            }

            if ($data['senha'] != $data['confirmacao']) {
                $json["message"] = $this->message->error(msg('pass_not_match'))->render();
                echo json_encode($json);
                return;
            }

            $objUser = new Account();
            $imei = session()->user->imei;
            $user = $objUser->find('imei=:imei', 'imei=' . $imei)->fetch();

            if (!$user) {
                $json["message"] = $this->message->error(msg('not_find_user'))->render();
                echo json_encode($json);
                return;
            }

            $user->senha = base64_encode($data['senha']);

            if (!$user->save()) {
                $json["message"] = $user->message->error(msg('not_find_user'))->render();
                echo json_encode($json);
                return;
            }

            $json["message"] = $this->message->success(msg('password_updated_successfully'))->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }



        echo $this->view->render("alterar-senha", array(
            "title" => label('change_password')
        ));
    }


    /**
     * trocar aparelho
     *
     * @param [type] $data
     * @return void
     */
    public function trocarAparelho($data = null)
    {
        $licenca = session()->user->licenca;
        $imei = session()->user->imei;

        if (!empty($data["csrf"])) {
            $data = filter_array($data);

            if (!check_csrf($data)) {
                $json["message"] = $this->message->error(msg("csrf"))->render();
                echo json_encode($json);
                return;
            }

            $objSoftware = new Software();
            $software = $objSoftware->find("licenca = :licenca", "licenca={$licenca}", 'validade')
                ->join('item', 'id_item', '', '', 'id_item', 'id_item')
                ->join('pedido', 'id_pedido', '', '', 'id_pedido', 'id_pedido', 'item')
                ->fetch(true);

            if (empty($software->id_item)) {
                $json["message"] = $this->message->error(msg('license_not'))->render();
                echo json_encode($json);
                return;
            }

            // atualiza uso para 0
            $objSoftware = new Software();
            $software = $objSoftware->find("licenca = :licenca AND id_item = :id_item", "licenca={$licenca}&id_item=" . $software->id_item)->fetch();
            $software->uso = 0;
            if (!$software->save()) {
                $json["message"] = $software->message()->render();
                echo json_encode($json);
                return;
            }

            // Deletar Mensagens
            $objMensagens = new Mensagens();
            $mensagens = $objMensagens->delete('imei=:imei', 'imei=' . $imei);
            if (!$mensagens) {
                $json["message"] = $objMensagens->message()->render();
                echo json_encode($json);
                return;
            }        

            // Deletar Account
            $objAccount = new Account();
            $account = $objAccount->delete('imei=:imei', 'imei=' . $imei);
            if (!$account) {
                $json["message"] = $objAccount->message()->render();
                echo json_encode($json);
                return;
            }


            $json["message"] = $this->message->success(msg('change_successfully_device'))->flash();
            $json["redirect"] = url("/login/");
            Auth::logout();

            echo json_encode($json);
            return;
        }
        echo $this->view->render("troca-aparelho", array(
            "title" => label('device_exchange')
        ));
    }


    /**
     * faq
     *
     * @return void
     */
    public function faq()
    {
        $logado = (empty(session()->user->imei) ? false : true);
        echo $this->view->render("faq", array('title' => label('frequently_asked_questions'), 'logado' => $logado));
    }



    /**
     * excluir relatorio
     *
     * @param [type] $data
     * @return void
     */
    public function excluirRelatorio($data = null)
    {


        $imei = session()->user->imei;
        $objMensagens = new Mensagens();
    
        $terms = 'imei = :imei';
        $termsAudio = $terms;
        $params = "imei={$imei}";

        // AJAX: Remover
        if (!empty($data['remove'])) {

            $terms .= (!empty($data['data1']) ? ' AND data >= :data1 ' : '');
            $terms .= (!empty($data['data2']) ? ' AND data <= :data2 ' : '');

            $termsAudio .= (!empty($data['data1']) ? ' AND nome >= :data1 ' : '');
            $termsAudio .= (!empty($data['data2']) ? ' AND nome <= :data2 ' : '');

            $params .= (!empty($data['data1']) ? '&data1=' . $data['data1'] . ' 00:00:00' : '');
            $params .=  (!empty($data['data2']) ? '&data2=' . $data['data2'] . ' 23:59:59' : '');


            if (empty($data['column'])) {
                $json["message"] = $this->message->error(msg('reports_not_found'))->flash();
                $json["reload"] = true;
                echo json_encode($json);
                return;
            } elseif ($data['column'] == 'mensagens') {
                $remove = $objMensagens->delete('imei=:imei', 'imei=' . $imei);
                if (!$remove) {
                    $json["message"] = $objMensagens->message()->flash();
                    $json["reload"] = true;
                    echo json_encode($json);
                    return;
                }
            }else {
                $json["message"] = $this->message->error(label('iInvalid_action'))->render();
                echo json_encode($json);
                return;
            }

            // se chegar aqui, então foi apagado com sucesos
            $json["message"] = $this->message->success(msg('successfully_deleted'))->flash();
            $json["reload"] = true;
            echo json_encode($json);
            return;
        }

        $terms .= (!empty($this->filter->data1) ? ' AND data >= :data1 ' : '');
        $terms .= (!empty($this->filter->data2) ? ' AND data <= :data2 ' : '');

        $termsAudio .= (!empty($this->filter->data1) ? ' AND nome >= :data1 ' : '');
        $termsAudio .= (!empty($this->filter->data2) ? ' AND nome <= :data2 ' : '');

        $params .= (!empty($this->filter->data1) ? '&data1=' . $this->filter->data1 . ' 00:00:00' : '');
        $params .=  (!empty($this->filter->data2) ? '&data2=' . $this->filter->data2 . ' 23:59:59' : '');

        //VIEW: Mostrar visão
        $total = new stdClass();
        $total->mensagens = $objMensagens->find($terms,  $params, 'id')->count();
    
        $this->filter->data = true;

        echo $this->view->render("excluir-relatorio", array(
            'title' => label('delete_reports'),
            'total' => $total,
            'filter' => $this->filter
        ));
    }
}
