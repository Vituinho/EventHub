<?php

namespace App\Controllers;

use MF\Controller\Action;
use MF\Model\Container;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class LoginController extends Action {

    public function Cadastro() {
        $cadastro = Container::getModel('Usuario');
        $this->view->usuarios = $cadastro->getAll();
        $this->render('cadastro');
    }

    public function Login() {
        $login = Container::getModel('Usuario');
        $this->view->usuarios = $login->getAll();
        $this->render('login');
    }

    public function Home() {
        $home = Container::getModel('Eventos');
        $this->view->eventos = $home->getAll();
        $this->render('home');
    }

    public function NovoUsuario() {
        $usuario = Container::getModel('Usuario');

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if (empty($telefone) || empty($nome) || empty($email) || empty($senha)) {
            header('Location: /cadastro?erro=2');
            exit;
        }

        if ($usuario->verificarEmail($email)) {
            header('Location: /cadastro?erro=0');
            exit;
        }

        if ($usuario->calcularForcaSenha($senha) < 3) {
            header('Location: /cadastro?erro=3');
            exit;
        }

        $usuario->__set('nome', $nome);
        $usuario->__set('email', $email);
        $usuario->__set('telefone', $telefone);
        $usuario->__set('senha', $senha);
        $usuario->salvar();

        header('Location: /');
        exit;
    }

    public function PainelAdmin() {
        require_once __DIR__ . '/../Auth.php';
        requireTipo();

        $painelAdmin = Container::getModel('Usuario');
        $eventos = Container::getModel('Eventos');
        $painelAdmin->__set('id_usuario', $_SESSION['id_usuario']);

        $this->view->usuario = $painelAdmin->getAll();
        $this->view->eventos = $eventos->getTudoMesmo();

        $this->render('painel_admin');
    }

    

    public function autenticar() {
        session_start();

        $usuario = Container::getModel('Usuario');
        $usuario->__set('email', $_POST['email']);
        $usuario->__set('senha', $_POST['senha']);

        $usuario_autenticado = $usuario->autenticar();

        if ($usuario_autenticado && !empty($usuario_autenticado->__get('id_usuario'))) {

            $id = $usuario_autenticado->__get('id_usuario');
            $email = $usuario_autenticado->__get('email');

            // Gera código 2FA e salva
            $codigo = rand(100000, 999999);
            $expira = date('Y-m-d H:i:s', time() + 300);

            $db = Container::getModel('TwoFA');
            $db->salvarCodigo($id, $codigo, $expira);

            // Importa PHPMailer manualmente
            require_once __DIR__ . '/../PHPMailer/PHPMailer.php';
            require_once __DIR__ . '/../PHPMailer/SMTP.php';
            require_once __DIR__ . '/../PHPMailer/Exception.php';

            $mail = new \PHPMailer\PHPMailer\PHPMailer(true);

            try {
                // Configuração SMTP
                $mail->isSMTP();
                $mail->Host = getenv('SMTP_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = getenv('SMTP_USER');
                $mail->Password = getenv('SMTP_PASS');
                $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Correção essencial da codificação!
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                // Debug opcional
                // $mail->SMTPDebug = 2;
                // $mail->Debugoutput = 'html';

                // Remetente e destino
                $mail->setFrom(getenv('SMTP_USER'), 'EventHub');
                $mail->addAddress($email);

                // Assunto e corpo
                $mail->Subject = 'Código de Verificação - EventHub';
                $mail->Body = "
                    <h2>Seu código de verificação</h2>
                    <p>Olá! Aqui está o seu código para entrar no EventHub:</p>
                    <h1><strong>$codigo</strong></h1>
                    <p>Ele expira em <strong>5 minutos</strong>.</p>
                ";
                $mail->isHTML(true);

                // Envia
                $mail->send();

                $_SESSION['2fa_user_id'] = $id;
                header("Location: /verificar/email");
                exit;

            } catch (\PHPMailer\PHPMailer\Exception $e) {
                echo "<h3>❌ Erro ao enviar e-mail:</h3>";
                echo "<pre>" . $mail->ErrorInfo . "</pre>";
                exit;
            }
        }

        header('Location: /?erro=1');
    }



    public function EditarUsuario() {
        session_start();

        $usuarioModel = Container::getModel('Usuario');

        if (isset($_POST['id_usuario']) && !isset($_POST['email'])) {
            $id_usuario = $_POST['id_usuario'];
            $usuario = $usuarioModel->getById($id_usuario);

            if ($usuario['id_usuario'] != $id_usuario && $_SESSION['tipo'] != 'ADMIN') {
                header('Location: /home');
                exit;
            }

            $this->view->usuario = $usuario;
            $this->render('editar_usuario');
            return;
        }

        if (isset($_POST['id_usuario'], $_POST['nome'], $_POST['email'], $_POST['telefone'], $_POST['tipo'])) {
            $id_usuario = $_POST['id_usuario'];
            $usuarioAtual = $usuarioModel->getById($id_usuario);

            if ($usuarioAtual['id_usuario'] != $id_usuario && $_SESSION['tipo'] != 'ADMIN') {
                header('Location: /home');
                exit;
            }

            $usuarioModel->__set('nome', $_POST['nome']);
            $usuarioModel->__set('email', $_POST['email']);
            $usuarioModel->__set('telefone', $_POST['telefone']);

            if (!empty($_POST['senha'])) {
                $novaSenha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
                $usuarioModel->__set('senha', $novaSenha);
            } else {
                $usuarioModel->__set('senha', $usuarioAtual['senha']);
            }

            $usuarioModel->__set('tipo', $_POST['tipo']);
            $usuarioModel->__set('id_usuario', $usuarioAtual['id_usuario']);
            $usuarioModel->atualizar();

            if ($_SESSION['tipo'] === 'ADMIN') {
                header('Location: /admin/painel');
            } else {
                header('Location: /home');
            }
            exit;
        }

        header('Location: /home');
        exit;
    }

    public function ExcluirUsuario() {
        session_start();
        $usuarioModel = Container::getModel('Usuario');
        $usuarioModel->ConfigurarCascade();

        if (isset($_POST['id_usuario']) && !isset($_POST['nome'])) {
            $id_usuario = $_POST['id_usuario'];
            $usuario = $usuarioModel->getById($id_usuario);

            if ($usuario['id_usuario'] != $_SESSION['id_usuario'] && $_SESSION['tipo'] != 'ADMIN') {
                header('Location: /home');
                exit;
            }

            $this->view->usuario = $usuario;
            $usuarioModel->DeletarUsuario($id_usuario);

            if ($_SESSION['tipo'] === 'ADMIN') {
                header('Location: /admin/painel');
            } else {
                header('Location: /home');
            }
            return;
        }

        header('Location: /home');
        exit;
    }

    public function PerfilUsuario() {
        session_start();
        $usuario = Container::getModel('Usuario');
        $id_usuario = $_SESSION['id_usuario'];

        $this->view->usuario = $usuario->getById($id_usuario);
        $this->render('perfil_usuario');
    }

    public function sobre() {
        $this->render('sobre');
    }

    public function VerificarEmail() {
        $this->view->erro = $_GET['erro'] ?? null;
        $this->render('verificar_email');
    }

    public function Processar2FA() {
        session_start();

        $user_id = $_SESSION['2fa_user_id'];
        $code = isset($_POST['code']) ? trim($_POST['code']) : '';

        $db = Container::getModel('TwoFA');
        $valido = $db->validarCodigo($user_id, $code);

        if ($valido) {
            $db->marcarUsado($valido['id']);

            $usuario = Container::getModel('Usuario');
            $dados = $usuario->getById($user_id);

            $_SESSION['id_usuario'] = $dados['id_usuario'];
            $_SESSION['nome'] = $dados['nome'];
            $_SESSION['email'] = $dados['email'];
            $_SESSION['telefone'] = $dados['telefone'];
            $_SESSION['tipo'] = $dados['tipo'];

            header("Location: /home");
            exit;
        }

        header("Location: /verificar/email?erro=1");
    }

    public function Logout() {
        session_start();
        session_destroy();
        header('Location: /');
    }
}

?>
