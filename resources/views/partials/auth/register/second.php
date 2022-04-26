<?php

$controller = load('controller');
$groups = $controller->loadModel('groups');

$groups->lazyLoadingSQL(['getGroups']);

?>

<div class="auth-title">
    <h3>Grupy przewodnie</h3>
    <span>Wybierz swoje grupy przewodnie</span>
</div>

<form class="form" method="POST" autocapitalize="off" autocomplete="off" id="form-second-register" action="/auth/register">
    <input type="hidden" value="<?= load('request')->query()->get('token')->value ?>" id="token" name="token">

    <div class="groups">
        <?php

            $input = '';

            foreach ($groups->getGroups as $key => $group) {
                $input .= '<input type="checkbox" value="'.$group['id'].'" name="group['.$key.']" id="group-'.$key.'">';
                $input .= '<label for="group-'.$key.'" style="' . load_image('vendor/groups/' . $group['image'], ['type' => 'bg']) . '">';
                $input .= '<h3>' . $group['name'] . '</h3>';
                $input .= '</label>';
            }

            echo $input;

        ?>
    </div>

    <div class="form-button">
        <button type="submit" class="custom-button color-white bg-color-green">
            <span>Utwórz konto</span>
        </button>
    </div>
</form>