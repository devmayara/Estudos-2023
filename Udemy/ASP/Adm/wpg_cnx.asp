<%
session.lcid = 1046

'Conectar ao banco de dados MySQL

Set Conexao = Server.CreateObject("ADODB.Connection")
Conexao.Open = "Driver=MySQL ODBC 5.2 ANSI Driver; database=cartaopremiar; server=mysql.cartaopremiar.com.br; uid=BANCO; password=SENHABANCO"
'Response.Write "<center><b>Conectado com sucesso ao banco de dados</center>"


%>