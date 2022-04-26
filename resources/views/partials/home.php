<?php
/**
 *
 * View definition for Home before logging module.
 *
 * @package MVC
 * @subpackage Home
 *
 */

$controller = load('controller');
$groups = $controller->loadModel('groups');
$users = $controller->loadModel('users');

$groups->lazyLoadingSQL(['getGroups', 'getUserGroups']);
$users->lazyLoadingSQL(['getUsers' => ['*', 'where' => ['active', 1]]]);

?>

<div class="home-page" id="home-page">
    <!-- Section welcome -->
    <section class="section-welcome" id="section-welcome">
        <div class="top" data-aos="fade-down">
            <h2>Witaj na</h2>

            <div class="logo">
                <h1><?= env('PROJECT_NAME') ?></h1>
            </div>
        </div>

        <div class="buttons">
            <a href="/auth/register" data-aos="fade-right" class="custom-button color-white bg-color-black">
                Utwórz nowe konto
            </a>

            <span data-aos="fade-down">lub</span>

            <a href="/auth/login" data-aos="fade-left" class="custom-button color-white bg-color-green">
                Zaloguj się
            </a>
        </div>
    </section>

    <!-- Section stats -->
    <section class="section-stats" id="section-stats">
        <ul class="vertical-list">
            <li data-aos="fade-right">
                <span class="material-icons-outlined">favorite_border</span>
                <div>
                    <h5>Grup przewodnich</h5>
                    <span><?= count($groups->getGroups) ?></span>
                </div>
            </li>

            <li data-aos="fade-down">
                <span class="material-icons-outlined">people_alt</span>
                <div>
                    <h5>Aktywnych użytkowników</h5>
                    <span><?= count($users->getUsers) ?></span>
                </div>
            </li>

            <li data-aos="fade-left">
                <span class="material-icons-outlined">loyalty</span>
                <div>
                    <h5>Grup użytkowników</h5>
                    <span><?= count($groups->getUserGroups) ?></span>
                </div>
            </li>
        </ul>
    </section>

    <!-- Section groups -->
    <section class="section-socials" id="section-socials">
        <h2 class="section-title" data-aos="fade-right">
            Domyślne grupy przewodnie
        </h2>

        <ul class="vertical-list">
            <?php

                $li = '';

                foreach ($groups->getGroups as $group) {
                    $li .= '<li data-aos="fade-left" title="'.$group['name'].'">';
                    $li .= '<a href="/group/' . $group['id'] . '" style="' . load_image('vendor/groups/' . $group['image'], ['type' => 'bg']) . '">';
                    $li .= '<h3>' . $group['name'] . '</h3>';
                    $li .= '</a>';
                    $li .= '</li>';
                }

                echo $li;

            ?>
        </ul>
    </section>

    <!-- Section user groups -->
    <section class="section-user-groups section-socials" id="section-user-groups">
        <h2 class="section-title" data-aos="fade-right">Grupy użytkowników</h2>

        <ul class="vertical-list">
            <?php

            $li = '';

            foreach ($groups->getUserGroups as $group) {
                $bg = $group['bg'];
                $logo = $group['logo'];

                $li .= '<li data-aos="fade-left" title="'.$group['name'].'">';
                $li .= '<a href="/group/' . $group['id'] . '" style="' .
                    load_user_file('groups/bg/' . $bg, [
                        'type' => 'bg',
                        'uuid' => $group['uuid'],
                        'file' => $bg,
                        'default' => 'bg-group-default.jpg',
                    ]) . '">';

                $li .= '<div class="group-logo" style="' . load_user_file('groups/logo/' . $logo, [
                        'type' => 'bg',
                        'uuid' => $group['uuid'],
                        'file' => $logo,
                        'default' => 'logo-group-default.jpg',
                    ]) . '"></div>';

                $li .= '<h3>' . $group['name'] . '</h3>';
                $li .= '</a>';
                $li .= '</li>';
            }

            echo $li;

            ?>
        </ul>
    </section>
</div>
