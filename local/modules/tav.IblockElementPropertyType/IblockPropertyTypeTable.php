<?
class IblockPropertyTypeTable
{
	/* Колонки таблицы:
		Name - id
		Title - Отображаемое имя
		Type - тип поля ввода text/textarea
	*/
	public static $arColumns = [
		[ 'Name' => 'project_id', 'Title' => 'Код проекта', 'Type' => 'text'],
		[ 'Name' => 'place', 'Title' => 'Место назначения', 'Type' => 'textarea'],
		[ 'Name' => 'organization', 'Title' => 'Организация', 'Type' => 'textarea'],
		[ 'Name' => 'target', 'Title' => 'Цель поездки', 'Type' => 'textarea'],
	];
	
	function GetUserTypeDescription()
    {
        return array(
                "PROPERTY_TYPE" => "S",
                "USER_TYPE" => "type_trip_table",
                "DESCRIPTION" => 'Таблица для регистрации служебных выездов',
				"ConvertToDB" => array("IblockPropertyTypeTable","ConvertToDB"),
                "ConvertFromDB" => array("IblockPropertyTypeTable","ConvertFromDB"),
				"GetPropertyFieldHtml" => array("IblockPropertyTypeTable","GetPropertyFieldHtml"),
                "GetPublicViewHTML" => array("IblockPropertyTypeTable","GetPublicViewHTML"),
                "GetPublicEditHTML" => array("IblockPropertyTypeTable","GetPublicEditHTML"),
		);
    }

	function GetPublicEditHTML($arProperty, $value, $strHTMLControlName)
	{	
		$arProp = IblockElementPropertyTypeTable::getPropertyValueById($arProperty['PROPERTY_VALUE_ID']);
		$arElementProp = unserialize($arProp['VALUE']);
		$strResult = self::GetPropertyHtml($arProperty['PROPERTY_VALUE_ID'], $arElementProp , $strHTMLControlName, true);
		return $strResult;
	}

	function GetPublicViewHTML($arProperty, $value, $strHTMLControlName)
	{
		$arProp = IblockElementPropertyTypeTable::getPropertyValueById($arProperty['PROPERTY_VALUE_ID']);
		$arElementProp = unserialize($arProp['VALUE']);
		$strResult = self::GetPropertyHtml($arProperty['PROPERTY_VALUE_ID'], $arElementProp, $strHTMLControlName, false);
		return $strResult;
	}
	
	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) 
	{
		parse_str($strHTMLControlName['VALUE'], $output);
		$arValueIds = array_keys($output['PROP'][$arProperty['ID']]);
		$PropertyValueId = $arValueIds[0];
		$arProp = IblockElementPropertyTypeTable::getPropertyValueById($PropertyValueId);
		$arElementProp = unserialize($arProp['VALUE']);
		$strResult = self::GetPropertyHtml($PropertyValueId, $arElementProp, $strHTMLControlName, true);
		return $strResult;
	}
	
	function ConvertToDB($arProperty, $value)
	{
		$result = array();
		if(is_array($value['VALUE']))
        {
            $result['VALUE'] = serialize($value['VALUE']);
			$result['DESCRIPTION'] = $value['DESCRIPTION'];
        }
		return $result;
	}
	
	function ConvertFromDB($arProperty, $value)
	{	
		$result = array();
		if (!is_array($value['VALUE']) && strlen($value['VALUE']) > 0)
		{
			$result['VALUE'] = unserialize($value['VALUE']);
			$result['DESCRIPTION'] = $value['DESCRIPTION'];
		}
		return $result;
	}
	
	function GetPropertyHtml($PropertyValueId, $arPropertyValue, $strHTMLControlName, $isEditable = true)
	{
		/* Получение html-кода для свойства */
		$strHtml = '<div class="edit_trip_table">';
		if ($isEditable) 
		{
			$strHtml .= self::GetHtmlTableHeader('PropTable'.$PropertyValueId);
			$strHtml .= self::GetHtmlTableRows($arPropertyValue,$strHTMLControlName);
			$strHtml .= self::GetHtmlTableFooter();
			$strHtml .= self::GetHtmlAddButton(strval('PropTable'.$PropertyValueId));
		}
		else
		{
			$strHtml .= self::GetHtmlTableHeader('ViewPropTable');
			$strHtml .= self::GetReadOnlyHtmlTableRows($arPropertyValue,$strHTMLControlName);
			$strHtml .= self::GetHtmlTableFooter();
		}
		$strHtml .= '</div>';
		return $strHtml;
	}
	
	function GetInputElement($Type = 'text', $Attribute, $value = '')
	{
		/* получение html-кода элемента ввода */
		switch ($Type)
		{
			case 'text':
				$strElement = '<input type="text" name="'.$Attribute.'" id="'.$Attribute.'" value="'.$value.'">';
			break;
			
			case 'textarea':
				$strElement = '<textarea rows="3" cols="20" name="'.$Attribute.'" id="'.$Attribute.'" >'.$value.'</textarea>';
			break;
		}
		return $strElement;
	}
	
	function GetHtmlTableRows($arRows, $strHTMLControlName) 
	{	
		/* получение html-кода строк таблицы для редактирования */
		if (!is_array($arRows) || empty($arRows))
		{
			$strEmptyRow .= '<tr id="prop-table-row-0" class="trip-table-row" >';
			foreach (self::$arColumns as $arColumn) 
			{
				$strEmptyRow .= '<td>';
				$strEmptyRow .= self::GetInputElement($arColumn['Type'], htmlspecialcharsbx($strHTMLControlName["VALUE"]).'[0]['.$arColumn['Name'].']');
				$strEmptyRow .= '</td>';
			}
			$strEmptyRow .= '</tr>';
			return $strEmptyRow;
		}
		
		$strHtmlRows = '';
		foreach ($arRows as $rowId => $row)
		{
			$strHtmlRows .= '<tr id="prop-table-row-'.$rowId.'" class="trip-table-row" >';
			foreach (self::$arColumns as $arColumn) 
			{
				$strHtmlRows .= '<td>';
				$strHtmlRows .= self::GetInputElement($arColumn['Type'], htmlspecialcharsbx($strHTMLControlName["VALUE"]).'['.$rowId.']['.$arColumn['Name'].']', $value = $row[$arColumn['Name']]);
				$strHtmlRows .= '</td>';
			}
			$strHtmlRows .= '</tr>';
		}
		
		return $strHtmlRows;
	}

	function GetReadOnlyHtmlTableRows($arRows, $strHTMLControlName) 
	{	
		/* получение html-кода строк таблицы для просмотра */
		if (!is_array($arRows) || empty($arRows))
		{
			$strHtmlRows = '<tr>';
				foreach (self::$arColumns as $arColumn)
				{
					$strHtmlRows .= '<td>&nbps;</td>';
				}
			$strHtmlRows .= '</tr>';
			return $strHtmlRows;
		}
		$strHtmlRows = '';
		foreach ($arRows as $rowId => $row)
		{
			$strHtmlRows .= '<tr id="prop-table-row-'.$rowId.'" class="trip-table-row" >';
			foreach (self::$arColumns as $arColumn)
			{
				$strHtmlRows .= '<td>'.$row[$arColumn['Name']].'</td>';
			}
			$strHtmlRows .= '</tr>';
		}
		return $strHtmlRows;
	}
	
	function GetHtmlTableHeader($AttrId = 'PropTable'){
		/* получение html-кода шапки таблицы */
		$strResult = '<table id="'.$AttrId.'"><tbody><tr "class="trip-table-header">';
		foreach (self::$arColumns as $arColumn)
		{
			$strResult .= '<th>'.$arColumn['Title'].'</th>';
		}
		$strResult .= '</tr>';
		return $strResult;
	}
	
	function GetHtmlTableFooter(){
		/* получение html-кода подвала таблицы */
		$result = '</tbody></table>';
		return $result;
	}
	
	function GetHtmlAddButton($AttrId = 'PropTable')
	{	
		/* получение html-кода кнопки добавления строки*/
		return '<input type="button" value="+ Добавить" class="trip-table-add-button" OnClick="addPropTableRow('.$AttrId.'); return false;" />';
	}
	
}
?>