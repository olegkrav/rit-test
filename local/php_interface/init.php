<?
CModule::AddAutoloadClasses(
        '', 
        array(
                'IblockPropertyTypeTable' => '/local/modules/tav.IblockElementPropertyType/IblockPropertyTypeTable.php',
                'IblockElementPropertyTypeTable' => '/local/modules/tav.IblockElementPropertyType/lib/IblockElementPropertyTypeTable.php',
				
        )
);

$arPropertyTypeTable = array(
   'js' => '/local/modules/tav.IblockElementPropertyType/IblockPropertyTypeTable.js',
   'css' => '/local/modules/tav.IblockElementPropertyType/style.css',
);
CJSCore::RegisterExt('PropertyTypeTable', $arPropertyTypeTable); 
CJSCore::Init(array("PropertyTypeTable"));
AddEventHandler('iblock', 'OnIBlockPropertyBuildList', array('IblockPropertyTypeTable', 'GetUserTypeDescription'));
?>