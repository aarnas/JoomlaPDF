<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Editor</title>
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

                    CKEDITOR.replace( 'editor1', { on: {'instanceReady': function (evt) { evt.editor.execCommand('maximize'); }}});

                    document.getElementById("editor1").style.display = "block";
                };
            </script>
        </form>
    </body>
</html>