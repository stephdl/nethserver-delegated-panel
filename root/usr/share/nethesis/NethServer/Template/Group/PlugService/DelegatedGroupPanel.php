<?php

echo $view->fieldsetSwitch('AdminAllPanels', 'enabled')->setAttribute('label', $T('AdminAllPanels_label'));

echo $view->fieldsetSwitch('AdminAllPanels', 'disabled', $view::FIELDSET_EXPANDABLE)
    ->setAttribute('label', $T('AdminEachPanel_label'))
    ->insert($view->selector('AdminPanels', $view::SELECTOR_MULTIPLE));
