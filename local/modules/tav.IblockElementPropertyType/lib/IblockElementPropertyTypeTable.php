<?
//namespace tav\IblockElementPropertyType;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;

class IblockElementPropertyTypeTable extends Entity\DataManager
{
	public static function getTableName()
	{
		return 'b_iblock_element_property';
	}
	
	public static function getMap()
	{
		return [
			(new IntegerField('ID'))
				->configurePrimary(true)
				->configureAutocomplete(true),

			new IntegerField('IBLOCK_PROPERTY_ID'),

			new IntegerField('IBLOCK_ELEMENT_ID'),

			new Reference(
				'ELEMENT', ElementTable::class,
				Join::on('this.IBLOCK_ELEMENT_ID', 'ref.ID')
			),

			new TextField('VALUE'),

			new StringField('VALUE_TYPE'),

			new IntegerField('VALUE_ENUM'),

			new FloatField('VALUE_NUM'),

			new StringField('DESCRIPTION'),

			new Reference(
				'ENUM',
				PropertyEnumerationTable::class,
				Join::on('this.VALUE_ENUM', 'ref.ID')
			),
		];
	}
	
	public static function getPropertyValueById($ID) 
	{
		$res = self::getById($ID)->fetch();
		return $res;
	}
	
	public static function getPropertiesList($arFilter = array()) 
	{
		return self::getList(['filter' => $arFilter])->fetchAll();
	}

	public static function updatePropertyValue($ID, $data) 
	{
		$res = self::update($ID, $data);
		return $res;
	}
}
	
?>	