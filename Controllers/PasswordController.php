<?php

require_once 'Core'.D_S.'Database.php';
require_once 'Core'.D_S.'Mailer.php';
require_once 'Core'.D_S.'Controller.php';
require_once 'Models'.D_S.'Utilisateur.php';

class PasswordController extends Controller
{
    /* send password resetting email */
    private function sendResettingEmail(Utilisateur $user)
    {
        $to = array($user->getEmail() => $user->getNomComplet());
        $params =  array(
            'uti'  => $user->getNomComplet(),
            'link' => $this->url('?page=password&action=reset&id='.$user->getToken())
        );
        $subject = "Demande de réinitialisation de mot de passe";
        $body    = $this->renderView('views'.D_S.'emails'.D_S.'password_reset.php', $params);
        $mail    = new Mailer($to, $subject, $body);
        $mail->send();
    }

    // action recover password
    public function recover()
    {
        if (!empty($_POST)) {
            $db       = Database::getInstance();
            $username = htmlspecialchars($_POST['username'], ENT_QUOTES);
            $user     = Utilisateur::findOneByLoginOrEmail($username);

            if (null === $user) {
                $this->render('Password/recover.php', array(
                        'invalid_id' => $username,
                        'template' => 'login',
                    ));
            }
            $user->setToken(Utilisateur::generateToken());
            $this->sendResettingEmail($user);
            $db->update($user->getId(), 'utilisateur', array('token' => $user->getToken()));
            $this->render('Password/request_sent.php', array('template' => 'login'));
            exit();

        }

        $this->render('Password/recover.php', array('template' => 'login'));
    }

    public function reset($token)
    {
        $db   = Database::getInstance();
        $user = Utilisateur::findOneBy(array('token' => $token));
        $errors = array();

        if (null === $user) {
            $this->redirect('?page=password&action=invalidLink');
        }

        if (!empty($_POST)) {
            $fields = $_POST;
            $errors = array_merge_recursive($errors,$this->validateBlank(array('mdp', 'mdp_confirmation')));
            if (!empty($fields['mdp'])) {
                $errors = array_merge_recursive($errors, $this->validatePasswordConfirmation());
                $fields['mdp'] = Utilisateur::encrypt($fields['mdp']); // on crypte
                unset($fields['mdp_confirmation']);
            } else {
                unset($fields['mdp']); // mot de passe non valide ne reset pas le mot de passe
                unset($fields['mdp_confirmation']);
            }

            if (empty($errors)) {
                $fields['token'] = '';
                $db->update($user->getId(), 'utilisateur', $fields);
                $auth = Auth::getInstance(); // on authentifie l'utilisateur si le token est valide
                $auth->authenticate($user);

                $this->redirect();
            } else {
                $_SESSION['form_errors'] = $errors;
                $this->redirect('?page=password&action=reset&id='.$token);
            }
        }
        $this->render('Password/reset.php', array('template' => 'login'));
    }

    public function change()
    {
        $db   = Database::getInstance();
        $user = $this->getUser();
        $errors = array();

        if (!empty($_POST)) {
            $fields = $_POST;
            $errors = array_merge_recursive($errors,$this->validateBlank(array('mdp', 'mdp_confirmation')));
            if (!empty($fields['mdp'])) {
                $errors = array_merge_recursive($errors, $this->validatePasswordConfirmation());
                $fields['mdp'] = Utilisateur::encrypt($fields['mdp']); // on crypte
                unset($fields['mdp_confirmation']);
            } else {
                unset($fields['mdp']); // mot de passe non valide ne reset pas le mot de passe
                unset($fields['mdp_confirmation']);
            }

            if (empty($errors)) {
                $db->update($user->getId(), 'utilisateur', $fields);

                $this->redirect('?page=password&action=change');
            } else {
                $_SESSION['form_errors'] = $errors;
                $this->redirect('?page=password&action=change');
            }
        }
        $this->render('Password/reset.php');
    }

    public function invalidLink()
    {
        $this->render('Password/invalid_link.php', array('template' => 'login'));
    }

    // action encrypt
    //permet d'encrypter un mot de passe entré dans l'url et afficher une version cryptée sur la page pour l'entrer en db
    public function encrypt($password)
    {
        echo BR;
        echo '<pre>'. Utilisateur::encrypt($password) . '</pre>'.BR;
    }
} 