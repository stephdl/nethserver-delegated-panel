<?php

/* @var $view \Nethgui\Renderer\Xhtml */
echo $view->header('groupname')->setAttribute('template', $T('Modify_header'));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

echo $view->fieldsetSwitch('AdminAllPanels', 'enabled')->setAttribute('label', $T('AdminAllPanels_label'));

echo $view->fieldsetSwitch('AdminAllPanels', 'disabled', $view::FIELDSET_EXPANDABLE)
    ->setAttribute('label', $T('AdminEachPanel_label'))
    ->insert($view->selector('AdminPanels', $view::SELECTOR_MULTIPLE));

echo $view->buttonList($view::BUTTON_SUBMIT | $view::BUTTON_CANCEL | $view::BUTTON_HELP);

