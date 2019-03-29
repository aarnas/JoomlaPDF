<?php
defined('_JEXEC') or die('Restricted access');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
    <input type="file" id="files" name="files[]" multiple />
    <output id="list"></output>
    <a href="update_users.php?person_id=<?php echo $row['person_id']; ?>" onclick="pop_up(this);">Parse</a>

    <script>
    function handleFileSelect(evt) {
        var files = evt.target.files; // FileList object

        // files is a FileList of File objects. List some properties.
        var output = [];
        for (var i = 0, f; f = files[i]; i++) {
        output.push('<li><strong>', escape(f.name), '</strong> (', f.type || 'n/a', ') - ',
                    f.size, ' bytes, last modified: ',
                    f.lastModifiedDate ? f.lastModifiedDate.toLocaleDateString() : 'n/a',
                    '</li>');
                    
        }
        document.getElementById('list').innerHTML = '<ul>' + output.join('') + '</ul>';
    }

    document.getElementById('files').addEventListener('change', handleFileSelect, false);
    
    function pop_up(url){
        window.open(url,'win2','status=no,toolbar=no,scrollbars=yes,titlebar=no,menubar=no,resizable=yes,width=1076,height=768,directories=no,location=no') 
    }
    </script>
</head>
<body>
<?php
    

    /*
    $home = file_get_contents("testSite.php");
    echo $home;
    file_get_contents("pdf.html", $home);

    echo "<script>
    var delayInMilliseconds = 5000;

    setTimeout(function() {
        var text = document.documentElement.innerHTML;
        text += '<style>*{color:black !important}</style>';

        download('pdftohtml.html',text);
    }, delayInMilliseconds);

    function download(filename, text) {
        var element = document.createElement('a');
        element.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
        element.setAttribute('download', filename);
    
        element.style.display = 'none';
        document.body.appendChild(element);
    
        element.click();
    
        document.body.removeChild(element);
    }


    </script>";
    */
?>
</body>
</html>