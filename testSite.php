<!DOCTYPE html>
<html>
<head>
    <title>Master</title>
</head>
<body>

<div id = "viewer">
<style>
    .textLayer {
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        color: #000;
        font-family: sans-serif;
    }

    .textLayer > div {
        color: transparent;
        position: absolute;
    
    }
    ::selection { background:rgba(0,0,255,0.3); }
    ::-moz-selection { background:rgba(0,0,255,0.3); }
</style>

</div>

<!-- Use latest PDF.js build from Github -->
<script type="text/javascript" src="pdf.js"></script>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript">
    //
    // NOTE: 
    // Modifying the URL below to another server will likely *NOT* work. Because of browser
    // security restrictions, we have to use a file server with special headers
    // (CORS) - most servers don't support cross-origin browser requests.
    //
    var url = 'ISI.pdf';

    //
    // Disable workers to avoid yet another cross-origin issue (workers need the URL of
    // the script to be loaded, and currently do not allow cross-origin scripts)
    //
    PDFJS.disableWorker = true;




    var pdfDoc = null,
        pageNum = 1,
        scale = 1,
        canvas = document.createElement('canvas'),
        ctx = canvas.getContext('2d');


    canvas.id = 'page';
    canvas.mozOpaque = true;

    //
    // Get page info from document, resize canvas accordingly, and render page
    //

    var container = document.getElementById('viewer');
    var div = document.createElement('div');
    var textLayerDiv = document.createElement('div');
        div.id = 'pageContainer';
        $(div).css("position", "relative")
        container.appendChild(div);
        div.appendChild(canvas)
        textLayerDiv.className = 'textLayer';
        div.appendChild(textLayerDiv);
// optimised CSS custom property getter/setter
var CustomStyle = (function CustomStyleClosure() {

  // As noted on: http://www.zachstronaut.com/posts/2009/02/17/
  //              animate-css-transforms-firefox-webkit.html
  // in some versions of IE9 it is critical that ms appear in this list
  // before Moz
  var prefixes = ['ms', 'Moz', 'Webkit', 'O'];
  var _cache = { };

  function CustomStyle() {
  }

  CustomStyle.getProp = function get(propName, element) {
    // check cache only when no element is given
    if (arguments.length == 1 && typeof _cache[propName] == 'string') {
      return _cache[propName];
    }

    element = element || document.documentElement;
    var style = element.style, prefixed, uPropName;

    // test standard property first
    if (typeof style[propName] == 'string') {
      return (_cache[propName] = propName);
    }

    // capitalize
    uPropName = propName.charAt(0).toUpperCase() + propName.slice(1);

    // test vendor specific properties
    for (var i = 0, l = prefixes.length; i < l; i++) {
      prefixed = prefixes[i] + uPropName;
      if (typeof style[prefixed] == 'string') {
        return (_cache[propName] = prefixed);
      }
    }

    //if all fails then set to undefined
    return (_cache[propName] = 'undefined');
  }

  CustomStyle.setProp = function set(propName, element, str) {
    var prop = this.getProp(propName);
    if (prop != 'undefined')
      element.style[prop] = str;
  }

  return CustomStyle;
})();

var TextLayerBuilder = function textLayerBuilder(textLayerDiv) {
  this.textLayerDiv = textLayerDiv;
  
  this.beginLayout = function textLayerBuilderBeginLayout() {
    this.textDivs = [];
    this.textLayerQueue = [];
  };

  this.endLayout = function textLayerBuilderEndLayout() {
    var self = this;
    var textDivs = this.textDivs;
    var textLayerDiv = this.textLayerDiv;
    var renderTimer = null;
    var renderingDone = false;
    var renderInterval = 0;
    var resumeInterval = 500; // in ms

    // Render the text layer, one div at a time
    function renderTextLayer() {
      if (textDivs.length === 0) {
        clearInterval(renderTimer);
        renderingDone = true;
        return;
      }
      var textDiv = textDivs.shift();
      if (textDiv.dataset.textLength > 0) {
        textLayerDiv.appendChild(textDiv);

        if (textDiv.dataset.textLength > 1) { // avoid div by zero
          // Adjust div width to match canvas text
          // Due to the .offsetWidth calls, this is slow
          // This needs to come after appending to the DOM
          var textScale = textDiv.dataset.canvasWidth / textDiv.offsetWidth;
          CustomStyle.setProp('transform' , textDiv,
            'scale(' + textScale + ', 1)');
          CustomStyle.setProp('transformOrigin' , textDiv, '0% 0%');
        }
      } // textLength > 0
    }
    renderTimer = setInterval(renderTextLayer, renderInterval);

    // Stop rendering when user scrolls. Resume after XXX milliseconds
    // of no scroll events
    var scrollTimer = null;
    function textLayerOnScroll() {
      if (renderingDone) {
        window.removeEventListener('scroll', textLayerOnScroll, false);
        return;
      }

      // Immediately pause rendering
      clearInterval(renderTimer);

      clearTimeout(scrollTimer);
      scrollTimer = setTimeout(function textLayerScrollTimer() {
        // Resume rendering
        renderTimer = setInterval(renderTextLayer, renderInterval);
      }, resumeInterval);
    }; // textLayerOnScroll

    window.addEventListener('scroll', textLayerOnScroll, false);
  }; // endLayout

  this.appendText = function textLayerBuilderAppendText(text,
                                                        fontName, fontSize) {
    var textDiv = document.createElement('div');

    // vScale and hScale already contain the scaling to pixel units
    var fontHeight = fontSize * text.geom.vScale;
    textDiv.dataset.canvasWidth = text.canvasWidth * text.geom.hScale;
    textDiv.dataset.fontName = fontName;

    textDiv.style.fontSize = fontHeight + 'px';
    textDiv.style.left = text.geom.x + 'px';
    textDiv.style.top = (text.geom.y - fontHeight) + 'px';
    textDiv.textContent = PDFJS.bidi(text, -1);
    textDiv.dir = text.direction;
    textDiv.dataset.textLength = text.length;
    this.textDivs.push(textDiv);
  };
};
    function renderPage(num) {
      // Using promise to fetch the page
      pdfDoc.getPage(num).then(function(page) {


        var viewport = page.getViewport(scale);
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        

        
        
        var textLayer = textLayerDiv ? new TextLayerBuilder(textLayerDiv) : null;
        console.log(textLayer);
        
        // Render PDF page into canvas context
        var renderContext = {
          canvasContext: ctx,
          viewport: viewport,
          textLayer: textLayer
        };
        page.render(renderContext);

      });
    
      // Update page counters
      document.getElementById('page_num').textContent = pageNum;
      document.getElementById('page_count').textContent = pdfDoc.numPages;


    }

    //
    // Go to previous page
    //
    function goPrevious() {
      reset()

      if (pageNum <= 1)
        return;
      pageNum--;
      renderPage(pageNum);
    }

    //
    // Go to next page
    //
    function goNext() {
    reset()       

      if (pageNum >= pdfDoc.numPages)
        return;
      pageNum++;
      renderPage(pageNum);
    }

    //
    // Asynchronously download PDF as an ArrayBuffer
    //
    PDFJS.getDocument(url).then(function getPdfHelloWorld(_pdfDoc) {
      pdfDoc = _pdfDoc;
      renderPage(pageNum);
      
    });

function reset()
{
    $(canvas).remove();
    $(".textLayer").empty()
    canvas = document.createElement('canvas'),
    ctx = canvas.getContext('2d');
    canvas.id = 'page';
    canvas.mozOpaque = true;
    $("#pageContainer").append(canvas)
}


    function getCanvasImage()
{

  $('#pageContainer').each(function(index, value) {
    var canvas = document.getElementById("page")
    var dataURL = canvas.toDataURL();
    console.log(dataURL)
    
    $(this).css("background", "url(" + dataURL +")")
    $(this).css("height", $(canvas).css("height"))
    $(this).css("width", $(canvas).css("width"))
    $(canvas).remove();

    console.log("done")
    //console.log(canvas.toDataURL())
//    console.log('div' + index + ':' + $(this).attr('id'));
  });
}
    function extract()
    {
        getCanvasImage();
        return;
    }
    function addCanvas()
    {

    }
  </script>  
  </body>
  </html>