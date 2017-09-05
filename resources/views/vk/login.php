<?php
// @codingStandardsIgnoreFile

use Jnjxp\Vk\Web\Action\Authenticate;

$action = $this->url(Authenticate::class);

$fields = [
    'Username' => [
        'name' => 'username',
        'attribs' => [
            'class' => 'form-control',
            'id' => 'input-username',
            'required' => true,
        ]
    ],
    'Password' => [
        'type' => 'password',
        'name' => 'password',
        'attribs' => [
            'class' => 'form-control',
            'id' => 'input-password',
            'required' => true,
        ]
    ]
];

$form = $this->form(
    [
        'id'     => 'login-form',
        'method' => 'post',
        'action' => $action
    ]
);

$errors = '';
if (isset($this->errors)) {
    foreach ($this->errors as $error) {
        $errors .= $this->ele(
            'p', $error, ['class' => 'alert alert-danger']
        );
    }
}


foreach ($fields as $label => $input) {

    $name = $input['name'];

    $error = (isset($this->errors) && isset($this->errors[$name]))
        ? reset($this->errors[$name])
        : null;

    $attr = ['class' => ['form-group']];
    if ($error) {
        $attr['class'][] = 'has-error';
    }

    $form .= $this->tag('div', $attr)
        . $this->label($label, ['for' => $input['attribs']['id']])
        . $this->input($input)
        . $error
        . $this->tag('/div');
}

$form .= $this->input(
    [
        'type'    => 'submit',
        'name'    => 'submit',
        'value'   => 'Login',
        'attribs' => [ 'class'   => 'btn btn-primary' ]
    ]
);

$form .= $this->tag('/form');

?>

<div class="container">
    <div class="page-header">
        <h1>Login</h1>
    </div>
    <?php echo $errors; ?>
    <?php echo $form; ?>
</div>


