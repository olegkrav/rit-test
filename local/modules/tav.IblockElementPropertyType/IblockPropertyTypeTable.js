function addPropTableRow(AttrId = 'PropTable') {
	console.log('addPropTableRow AttrId: ', AttrId);
	obTable = BX(AttrId);
	obBody = BX.findChild(obTable, {tag: 'tbody'});
	arRows = BX.findChildren(obBody ,{tag: 'tr', className: 'trip-table-row'});
	newRow = arRows[0].cloneNode(true);
	this.clearElements(newRow,'input');
	this.clearElements(newRow,'textarea');
	this.setNewAttrs(newRow, arRows.length,'textarea');
	this.setNewAttrs(newRow, arRows.length,'input');
	BX.append(newRow,obBody);
}	

function clearElements(Source, ClearTagsName) {
	arElements = BX.findChildren(Source, {tag: ClearTagsName}, true);
	arElements.forEach(function(el){
		el.value = '';
	});
}

function setNewAttrs(Source, NewId, ClearTagsName) {
	arElements = BX.findChildren(Source, {tag: ClearTagsName}, true);
	arElements.forEach(function(el){
		el.name = el.name.replace('[VALUE][0]', '[VALUE]['+NewId+']');
		el.id = el.id.replace('[VALUE][0]', '[VALUE]['+NewId+']');
	});
}