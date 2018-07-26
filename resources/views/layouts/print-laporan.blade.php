<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title></title>

    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link  href="/assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    @yield('css')
    @stack('css')
   <style type="text/css">
    /* http://meyerweb.com/eric/tools/css/reset/ 
   v2.0 | 20110126
   License: none (public domain)
    */

      html, body, div, span, applet, object, iframe,
      h1, h2, h3, h4, h5, h6, p, blockquote, pre,
      a, abbr, acronym, address, big, cite, code,
      del, dfn, em, img, ins, kbd, q, s, samp,
      small, strike, strong, sub, sup, tt, var,
      b, u, i, center,
      dl, dt, dd, ol, ul, li,
      fieldset, form, label, legend,
      table, caption, tbody, tfoot, thead, tr, th, td,
      article, aside, canvas, details, embed, 
      figure, figcaption, footer, header, hgroup, 
      menu, nav, output, ruby, section, summary,
      time, mark, audio, video {
        margin: 0;
        padding: 0;
        border: 0;
        font-size: 100%;
        font: inherit;
        vertical-align: baseline;
      }
      /* HTML5 display-role reset for older browsers */
      article, aside, details, figcaption, figure, 
      footer, header, hgroup, menu, nav, section {
        display: block;
      }
      body {
        line-height: 1;
      }
      ol, ul {
        list-style: none;
      }
      blockquote, q {
        quotes: none;
      }
      blockquote:before, blockquote:after,
      q:before, q:after {
        content: '';
        content: none;
      }
      table {
        border-collapse: collapse;
        border-spacing: 0;
      }

    @page {
        size: auto;  /* auto is the initial value */
        margin: 0mm; /* this affects the margin in the printer settings */
      }
      html {
        background-color: #FFFFFF;
        margin: 0px; /* this affects the margin on the HTML before sending to printer */
      }
      body {
        /*border: solid 1px blue;*/
        margin: 5mm 7mm 7mm 5mm; /* margin you want for the content */
        /*font: 10px/1 'Century Gothic', sans-serif;*/
        transform: scale(1,0.75);
      }

      .table th, .table td, .table, table{
    font: 13px 'Century Gothic', sans-serif !important;
      border-top: none !important;
      border-left: none !important;
      border-bottom: none !important;
    }
    h1 { 
    display: block;
    font-size: 2em;
    margin-top: 0.67em;
    margin-bottom: 0.67em;
    margin-left: 0;
    margin-right: 0;
    font-weight: bold;
    }
    h2 {
        display: block;
        font-size: 1.5em;
        margin-top: 0.83em;
        margin-bottom: 0.83em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }
    h3 { 
        display: block;
        font-size: 1.17em;
        margin-top: 1em;
        margin-bottom: 1em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }
    h4 { 
        display: block;
        margin-top: 1.33em;
        margin-bottom: 1.33em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }
    h5 { 
        display: block;
        font-size: .83em;
        margin-top: 1.67em;
        margin-bottom: 1.67em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }
    h6 { 
        display: block;
        font-size: .67em;
        margin-top: 2.33em;
        margin-bottom: 2.33em;
        margin-left: 0;
        margin-right: 0;
        font-weight: bold;
    }
    hr{
      border-top:1px solid #000;
      margin-top:1px;
      margin-bottom:0px;
    }
    #hrmargin{
      margin-top:4px;
      margin-bottom: 8px;
    }
    #infors{
      line-height:13px;
    }
    img{
      width:70px;
    }
    #box_img{
      border: solid 1px  #6A97F6;
      margin-left : 10px;
      margin-right :10px;
      width: 80px;
    }
    #tabel_isi{
      font-size: 12px;
    }
    #w40{
      width:40px;
    }
    #w60{
      width:60px;
    }
    #w80{
      width:80px;
    }
    #text_right{
      text-align: right;
    }
    #mr100{
      margin-right :100px;
    }
    #w100{
      width:100px;
    }
    #text_indent{
      text-indent : 0.3in;
    }

      .print-bar {
          position: fixed;
          bottom: 0;
          z-index: 10001;
          left: 0;
          width: 100%;
          border: none;
          padding: 20px 0;
          cursor: pointer;
          background-color: #36C6D3;
          color: #fff;
          text-transform: uppercase;
          font-weight: bold;
      }

      .print-bar:hover {
          background-color: #27A4B0;
      }

      @media print {
          #btn-print {
              display: none;
          }

          .page-break {
              page-break-before: always;
          }
          .print-bar,
          .btn {
              display: none;
          }

      }
    </style>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>
  <body>
        <div  id="print-area">
          @yield('content')
        </div>
        <button type="button" class="print-bar" id="btn-print">
  <i class="fa fa-print"></i> Klik Disini Untuk Mulai Mencetak
</button>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js" type="text/javascript"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/assets/js/bootstrap.min.js" type="text/javascript"></script>
    <script type="text/javascript">

      $(document).ready(function() {
        $('#btn-print').on('click',function(){
        //   $('#btn-print').parent().hide();
          window.print();
          window.close();
        //   $('#btn-print').parent().show();
        });
      });

    </script>
    @yield('js')
    @stack('js')
  </body>
</html>
