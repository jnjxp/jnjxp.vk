<?php
// @codingStandardsIgnoreFile

use Jnjxp\Vk\Web\Action\Logout;

$action = $this->url(Logout::class);

$form = $this->form(
    [
        'id'     => 'logout-form',
        'method' => 'post',
        'action' => $action
    ]
);

$form .= $this->input(
    [
        'type'    => 'submit',
        'name'    => 'submit',
        'value'   => 'Logout'
    ]
);

$form .= $this->tag('/form');

?>

<?php echo $form; ?>


