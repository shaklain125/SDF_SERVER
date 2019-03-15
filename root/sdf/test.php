<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
    <style media="screen">
    </style>
  </head>
  <body>
    <script type="text/javascript">
      window.onload = Onload()
      var c;
      var submit;
      function Onload() {
        c = new ConfirmDialog(yes, no);
        c.show();
        submit = false;
      }
      function yes() {
        submit = true;
      }
      function no() {
        submit = false;
      }
    </script>
    <div>
      contentHERE;
    </div>
  </body>
</html>
