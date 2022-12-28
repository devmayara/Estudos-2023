<!--#include file="topo.asp"-->  
<!--#include file="wpg_cnx.asp"-->  
<%

'sql = "select * from TABELA order by CAMPO "
'set rs = conexao.execute(sql)

%>
  <script>
function Excluir(cod)
   {
	 if(confirm("Confirma exclusão?"))
	  {
	    parent.location = "manu_modelo.asp?opc=exc&cod=" + cod ;
      }
   
   } 
 	
 </script>

<div class="container">
<form action="frm_modelo.asp" method=post>
<button type="submit" class="btn btn-primary">Adicionar</button>

  <h4>Plano</h4>
  <div class="table-responsive">          
  <table class="table">
    <thead>
      <tr>
        <th>#</th>
        <th>Coluna 1</th>
        <th>Coluna 2</th>
        <th>#</th>		
      </tr>
    </thead>
    <tbody>
<%
'AQUI COMEÇA O DO WHILE ONDE TRARÁ AS LINHAS DE ACORDO COM O SELECT FEITO
'do while not rs.eof%>
      <tr>
        <td>
 <a href="frm_modelo.asp?evt=alt&cod=<%'=rs("codigo")%>">
<img src="imagens/alt.jpg">
        </a>		
		</td>
        <td>Campo1 <%'=rs("CAMPO1")%></td>
        <td>Campo2<%'=rs("CAMPO2")%></td>
        <td>
 <a href="javascript:Excluir(<%'=rs("cd_plano")%>)">
 <img src="imagens/excluir.jpg">
        </a>		
		</td>

      </tr>
<%
'LOOP DEPOIS DA LINHA PARA QUE SE REPITA ENQUANTO TIVER REGISTROS NO SELECT FEITO
'rs.movenext
'loop
%>	  
    </tbody>
  </table>

  </div>
</form>

</div>
</body>
</html>