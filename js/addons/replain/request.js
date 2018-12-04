function replain_call() {
    var http = new XMLHttpRequest();
    var url = 'index.php';
    var params = 'dispatch=replainfront.activate&key=' + Replainkey;
    http.open('POST', url, false);

    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    http.send(params);
}