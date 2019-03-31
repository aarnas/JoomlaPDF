<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Editor</title>
        <button value="Refresh Page" onClick="window.location.reload()">Reload Page</button>
        <input type='file' id='file' onchange='openFile(event)'><br>
        <!-- Make sure the path to CKEditor is correct. -->
        <script src="ckeditor\ckeditor.js"></script>
    </head>
    <body>
        <form>
            <textarea name="editor1" id="editor1">
            </textarea>
            <script>
                document.getElementById("editor1").style.display = "none";
                
                var openFile = function(event) {
                    document.getElementById("file").style.display = "none";
                    var input = event.target;
                    var reader = new FileReader();
                    reader.onload = function(){
                        var text = reader.result;
                        document.getElementById("editor1").value += text;
                    };
                    reader.readAsText(input.files[0]);

                    CKEDITOR.replace( 'editor1' );

                    document.getElementById("editor1").style.display = "block";
                };

                // Replace the <textarea id="editor1"> with a CKEditor
                // instance, using default configuration.
                //CKEDITOR.replace( 'editor1' );
            </script>
        </form>
    </body>
</html>