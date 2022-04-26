<?php
/**
 *
 * View definition for User module.
 *
 * @package MVC
 * @subpackage Home
 *
 */

?>

<div id="navbar-page">
    <div class="sidebar-page home-page navbar-page">
        <section class="navbar-section-groups">
            <ul class="menu-list">
                <li class="<?= activeNavItem('home') ?>">
                    <a href="/">
                        <span class="material-icons-outlined">home</span>
                        <span>Główna</span>
                    </a>
                </li>

                <li class="<?= activeNavItem('user/profile') ?>">
                    <a href="<?= route('user/profile') ?>">
                        <span class="material-icons-outlined">face</span>
                        <span>Profil</span>
                    </a>
                </li>

                <li class="<?= activeNavItem('user/default-groups') ?>">
                    <a href="<?= route('user/default-groups') ?>">
                        <span class="material-icons-outlined">favorite_border</span>
                        <span>Grupy przewodnie</span>
                    </a>
                </li>

                <li class="<?= activeNavItem('user/groups') ?>">
                    <a href="<?= route('user/groups') ?>">
                        <span class="material-icons-outlined">loyalty</span>
                        <span>Grupy</span>
                    </a>
                </li>

                <li class="<?= activeNavItem('user/friends') ?>">
                    <a href="<?= route('user/friends') ?>">
                        <span class="material-icons-outlined">people</span>
                        <span>Znajomi</span>
                    </a>
                </li>

                <li class="<?= activeNavItem('user/settings') ?>">
                    <a href="<?= route('user/settings') ?>">
                        <span class="material-icons-outlined">settings</span>
                        <span>Ustawienia</span>
                    </a>
                </li>
            </ul>
        </section>
    </div>

    <span class="navbar-copyright"><?= env('PROJECT_NAME').' © '.date('Y') ?></span>
</div>
