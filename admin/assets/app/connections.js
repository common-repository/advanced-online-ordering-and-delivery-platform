function setupConnection() {
    $(document).on('click', '.read-cloverconnection-button', function(){
        showCloverConnection();
    });
    $(document).on('click', '.read-datasync-button', function(){
        showDataSync();
    });
    $(document).on('click', '.read-manualimport-button', function(){
        showManualImport();
    });
}

function showCloverConnection() {
	var elem = $('.read-cloverconnection-button');
	activateTab(elem);
	loadTemplate('clover-connection',"#connection-content", function(){});
}

function showDataSync() {
	var elem = $('.read-datasync-button');
	activateTab(elem);
	loadTemplate('data-synchronization',"#connection-content", function(){});
}

function showManualImport() {
	var elem = $('.read-manualimport-button');
	activateTab(elem);
	loadTemplate('manual-import',"#connection-content", function(){});
}