<?php

/* @var $view \Nethgui\Renderer\Xhtml */
echo $view->header('username')->setAttribute('template', $T('Modify_header'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);
echo $view->checkBox('AdminAllPanels','enabled')->setAttribute('uncheckedValue', 'disabled');
echo $view->selector('AdminPanels', $view::SELECTOR_MULTIPLE);
echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

