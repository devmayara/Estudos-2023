<!--#include file="topo.asp"-->  
<!--#include file="wpg_cnx.asp"-->  
<br><br>
<div class="alert alert-primary" role="alert">
  Olá Alisson, <br>seja bem-vindo ao Curso de Desenvolvimento Web
</div>

<br><br>
<%
' TESTANDO SE EXISTE MENSAGEM NA TABELA DE CONTATO

sql = "select * from tb_contato order by cd_contato "
set rs = conexao.execute(sql)

if not rs.eof then
%>
<div class="alert alert-primary" role="alert">
  Nova(s) Mensagem(s)<br>
<%do while not rs.eof%>  
 <%=rs("nome")%> - <%=rs("assunto")%><br>
<%rs.movenext
loop%>
</div>

<%

end if

%>

</body>
</html>