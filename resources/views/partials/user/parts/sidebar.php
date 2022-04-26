<?php
/**
 *
 * View definition for User module.
 *
 * @package MVC
 * @subpackage Home
 *
 */

$controller = load('controller');
$user = $controller->loadModel('user/user');

$user->lazyLoadingSQL(['getUserDefaultGroups', 'getUsersGroups', 'getUserGroups', 'getUserFriends']);

?>

<div id="sidebar-page">
    <div class="sidebar-page home-page">
        <section class="sidebar-section-groups section-socials">
            <h2 class="section-title">
                Grupy przewodnie
            </h2>

            <ul class="vertical-list">
                <?php

                $li = '';

                foreach ($user->getUserDefaultGroups as $group) {
                    $li .= '<li title="'.$group['name'].'">';
                    $li .= '<a href="/group/' . $group['id'] . '" style="' . load_image('vendor/groups/' . $group['image'], ['type' => 'bg']) . '">';
                    $li .= '<h3>' . $group['name'] . '</h3>';
                    $li .= '</a>';
                    $li .= '</li>';
                }

                echo $li;

                ?>
            </ul>

            <?php if ($user->checkAccess('joined-main-groups')) : ?>
                <div class="join-to-group">
                    <a href="" class="custom-link">
                        Dołącz do nowej grupy przewodniej
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <div class="separator"></div>

        <section class="sidebar-section-groups section-user-groups section-socials">
            <h2 class="section-title">
                Grupy użytkowników
            </h2>

            <ul>
                <?php if (!empty($user->getUsersGroups)) : ?>
                    <?php

                    $li = '';

                    foreach ($user->getUsersGroups as $group) {
                        $bg = $group['bg'];
                        $logo = $group['logo'];

                        $li .= '<li title="'.$group['name'].'">';
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
                <?php else : ?>
                    <li class="none-data">
                        <div class="alert bg-color-warning">
                            <span>Brak grup do wyświetlenia.</span>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($user->checkAccess('joined-users-groups')) : ?>
                <div class="join-to-group">
                    <a href="" class="custom-link">
                        Dołącz do innych grup
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <div class="separator"></div>

        <section class="sidebar-section-groups section-user-groups section-socials">
            <h2 class="section-title">
                Twoje grupy
            </h2>

            <ul>
                <?php if (!empty($user->getUserGroups)) : ?>
                    <?php

                    $li = '';

                    foreach ($user->getUserGroups as $group) {
                        $bg = $group['bg'];
                        $logo = $group['logo'];

                        $li .= '<li title="'.$group['name'].'">';
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
                <?php else : ?>
                    <li class="none-data">
                        <div class="alert bg-color-warning">
                            <span>Nie masz jeszcze utworzonej grupy.</span>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($user->checkAccess('created-groups')) : ?>
                <div class="join-to-group">
                    <a href="" class="custom-link">
                        Utwórz własną grupę
                    </a>
                </div>
            <?php endif; ?>
        </section>

        <div class="separator"></div>

        <section class="sidebar-section-groups section-user-groups section-socials">
            <h2 class="section-title">
                Twoi znajomi
            </h2>

            <ul>
                <?php if (!empty($user->getUserFriends)) : ?>
                    <?php

                    $li = '';

                    foreach ($user->getUserFriends as $friend) {
                        $bg = $friend['bg'];
                        $avatar = $friend['avatar'];

                        $li .= '<li>';
                        $li .= '<a href="/user/' . $friend['id'] . '" style="' .
                            load_user_file('images/' . $bg, [
                                'type' => 'bg',
                                'uuid' => $friend['uuid'],
                                'file' => $bg,
                                'default' => 'bg-group-default.jpg',
                            ]) . '">';

                        $li .= '<div class="group-logo" style="' . load_user_file('images/' . $avatar, [
                                'type' => 'bg',
                                'uuid' => $friend['uuid'],
                                'file' => $avatar,
                                'default' => 'avatar-default.png',
                            ]) . '"></div>';

                        $li .= '<h3>' . $friend['username'] . '</h3>';
                        $li .= '</a>';
                        $li .= '</li>';
                    }

                    echo $li;

                    ?>
                <?php else : ?>
                    <li class="none-data">
                        <div class="alert bg-color-warning">
                            <span>Nie masz jeszcze żadnych znajomych.</span>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if ($user->checkAccess('joined-friends')) : ?>
                <div class="join-to-group">
                    <a href="" class="custom-link">
                        Zaproś nowych znajomych
                    </a>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>
