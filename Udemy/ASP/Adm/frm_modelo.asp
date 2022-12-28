<!--#include file="topo.asp"-->  
<!--#include file="wpg_cnx.asp"-->  

<%
'RECEBENDO CÓDIGO COMO PARAMETRO E EVT PARA SABER SE VAI INCLUIR OU ALTERAR
cod = Request.QueryString("cod")
evt  = Request.QueryString("evt")
if cod <> "" then
   cod = cint(cod)
end if
if ucase(evt) = "ALT" then
'   sql = "SELECT * from TABELA where CODIGO = "&cod
'  Response.Write(sql)
'  Response.End()
'   set rs = conexao.execute(sql)
'   VARIAVEL    = rs("CAMPO") 

   bot        = "Alterar"
else
   bot       = "Incluir"
end if

%>

<div class="container">
<br><br>
  <form class="form-horizontal" action="manu_modelo.asp" method="post">

    <div class="form-group">
      <label class="control-label col-sm-2" for="nome"><b>Campo 1:</b></label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="nome" placeholder="Plano" name="" value="<%=variavel%>">
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-sm-2" for="nome"><b>Campo 2:</b></label>
      <div class="col-sm-4">
        <input type="text" class="form-control" id="nome" placeholder="Valor" name="" value="<%=variavel%>">
      </div>
    </div>
    
    <div class="form-group">        
      <div class="col-sm-offset-2 col-sm-10">
        <button type="submit" class="btn btn-default" name="Botao" value="<%=bot%>"><%=bot%></button>
      </div>
    </div>
	<input type="hidden" name="hfcod" value="<%=cod%>">	
  </form>
</div>

</body>
</html>