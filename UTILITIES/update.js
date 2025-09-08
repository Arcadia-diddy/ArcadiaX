window.onblur = () => {
  document.title = "Google Docs";
  document.getElementById("favicon").href =
    "https://ssl.gstatic.com/docs/doclist/images/mediatype/icon_1_document_x16.png";
};
window.onfocus = () => {
  document.title = "ArcadiaX";
  document.getElementById("favicon").href = "favicon-32x32.png";
};
