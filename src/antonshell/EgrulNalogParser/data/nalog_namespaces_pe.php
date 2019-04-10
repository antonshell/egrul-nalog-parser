<?php

use antonshell\EgrulNalogParser\classes\DocumentNamespace;

return [
    new DocumentNamespace(
        'common',
        'Фамилия, имя, отчество (при наличии'
    ),
    new DocumentNamespace(
        'citizen',
        'Сведения о гражданстве'
    ),
    new DocumentNamespace(
        'pe_register_info',
        'Сведения о регистрации индивидуального предпринимателя'
    ),
    new DocumentNamespace(
        'register_org_info',
        'Сведения о регистрирующем органе по месту жительства индивидуального'
    ),
    new DocumentNamespace(
        'taxes',
        'Сведения об учете в налоговом органе'
    ),
    new DocumentNamespace(
        'pension',
        'Сведения о регистрации в качестве страхователя в территориальном органе'
    ),
    new DocumentNamespace(
        'main_activity',
        'Сведения об основном виде деятельности',
        true
    ),
    new DocumentNamespace(
        'extra_activity',
        'Сведения о дополнительных видах деятельности'
    ),
    new DocumentNamespace(
        'egrip',
        'Сведения о записях, внесенных в ЕГРИП',
        true,
        true
    ),
];