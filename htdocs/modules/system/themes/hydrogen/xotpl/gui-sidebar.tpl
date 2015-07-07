<nav class="hydrogen-sidebar">
    <ul>
        <li>
            <a href="<{$xoops_url}>/admin.php">
                <{xoicon icon="xicon-home"}>
                <span class="caption"><{$hydrogen.lang.dashboard}></span>
            </a>
        </li>
        <{if $hydrogen.currentModule}>
            <li class="nav-separator current">
                <a href="#" data-siblings="currents">
                    <span class="caption"><{$hydrogen.currentModule.name}></span>
                    <span class="indicator"><{$hydrogen.currentModule.name|substr:0:1}></span>
                </a>
            </li>
            <{foreach item=menu from=$hydrogen.currentModule.menu}>
                <li class="currents <{if $menu.options}> menu<{/if}><{if $menu.location && $menu.location==$hydrogen.location}> current-menu<{/if}>">
                    <a href="<{link menu=$menu module=$hydrogen.currentModule.dirname}>">
                        <{xoicon icon=$menu.icon}>
                        <span class="caption"><{$menu.title}></span>
                    </a>
                    <{if $menu.options}>
                        <ul class="nav-menu">
                            <{foreach item=submenu from=$menu.options}>
                                <li>
                                    <a href="<{link menu=$submenu module=$hydrogen.currentModule.dirname}>">
                                        <{$submenu.title}>
                                    </a>
                                </li>
                            <{/foreach}>
                        </ul>
                    <{/if}>
                </li>
            <{/foreach}>
        <{/if}>
        <li class="nav-separator">
            <a href="#" data-siblings="modules">
                <span class="caption"><{$hydrogen.lang.modules}></span>
                <span class="indicator">M</span>
            </a>
        </li>
        <{foreach item=module from=$hydrogen.modules}>
            <{if $module.dirname!=$hydrogen.currentModule.dirname}>
                <li class="modules <{if $module.menu}> menu<{/if}>">
                    <a href="<{$module.link}>">
                        <{$module.icon}>
                        <span class="caption"><{$module.name}></span>
                    </a>
                    <{if $module.menu}>
                        <ul class="nav-menu">
                            <{foreach item=menu from=$module.menu}>
                                <li>
                                    <a href="<{link menu=$menu module=$module.dirname}>">
                                        <{$menu.title}>
                                    </a>
                                </li>
                            <{/foreach}>
                        </ul>
                    <{/if}>
                </li>
            <{/if}>
        <{/foreach}>
        <li class="nav-separator">
            <a href="#" data-siblings="extension">
                <span class="caption"><{$hydrogen.lang.extensions}></span>
                <span class="indicator">E</span>
            </a>
        </li>
        <{foreach item=module from=$hydrogen.extensions}>
            <li class="extension<{if $module.menu}> menu<{/if}>">
                <a href="<{$module.link}>">
                    <{$module.icon}>
                    <span class="caption"><{$module.name}></span>
                </a>
                <{if $module.menu}>
                    <ul class="nav-menu">
                        <{foreach item=menu from=$module.menu}>
                            <li>
                                <a href="<{link menu=$menu module=$module.dirname}>">
                                    <{$menu.title}>
                                </a>
                            </li>
                        <{/foreach}>
                    </ul>
                <{/if}>
            </li>
        <{/foreach}>
    </ul>
</nav>
