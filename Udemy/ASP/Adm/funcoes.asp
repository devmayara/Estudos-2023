<%  
Function Hex2Dec( hexadecimal )
   dim n, aux, valores, decimal, fator
   valores = "0123456789ABCDEF"
   n = 1: decimal = 0
   for n = len( hexadecimal ) to 1 step -1
      fator = 16 ^ ( len( hexadecimal ) - n )
      aux = InStr( valores, mid( hexadecimal, n, 1 ) ) - 1
      decimal = decimal + ( aux * fator )
   next
   Hex2Dec = decimal
End Function

Function Virgula_ponto(vl)
Virgula_ponto    =  replace(vl,".","")
Virgula_ponto    =  replace(vl,",",".")
End Function

Function ZerosEsquerda(Num,tam)
	Dim Zero
	Num = Trim(Num)
	Zero = ""
	For i = Len(Num) To tam - 1
	    Zero = "0" & Zero
	Next
	ZerosEsquerda = Trim(Zero & Num)	
End Function

function data_banco(data)
   if year(data) = null then
	  data_banco = null
   else
	  data_banco = year(data)&ZerosEsquerda(month(data),2)&ZerosEsquerda(day(data),2)
   end if
end function

function data_hora_banco(data)
   if year(data) = null then
	  data_hora_banco = null
   else
	  data_hora_banco = year(data)&ZerosEsquerda(month(data),2)&ZerosEsquerda(day(data),2)& " "&ZerosEsquerda(hour(data),2)&":"&ZerosEsquerda(minute(data),2)&":"&ZerosEsquerda(second(data),2)
   end if
end function

Function data_completa(data)
	dia = ZerosEsquerda(day(data),2)
	mes = ZerosEsquerda(month(data),2)
	ano = ZerosEsquerda(year(data),2)
	response.write dia&"/"&mes&"/"&ano
End Function

Function data_completa2(data)
	dia = ZerosEsquerda(day(data),2)
	mes = ZerosEsquerda(month(data),2)
	ano = ZerosEsquerda(year(data),2)
	data_completa2 = dia&"/"&mes&"/"&ano
End Function

function banco_data(data)
	dia = ZerosEsquerda(day(data),2)
	mes = ZerosEsquerda(month(data),2)
	ano = ZerosEsquerda(year(data),2)
	banco_data = dia&"/"&mes&"/"&ano
'   banco_data = mid(data,7,2)&"/"&mid(data,5,2)&"/"&mid(data,1,4)
end function

function idade(datanascimento)
	ano_atual=year(date())'Extrai ano do atual
	mes_atual=month(date())'Extrai mes atual
	ano_niver=year(datanascimento)'Extrai ano do nascimento
	mes_niver=month(datanascimento)'Extrai mÍs do nascimento
	dif_ano=cint(ano_atual)-cint(ano_niver)'Faz a diferenÁa dos anos
	dif_mes=cint(mes_atual)-cint(mes_niver)'Faz a diferenÁa dos meses
	'Verifica se a diferenÁa dos meses È negativa, 
	'se for e pq ainda n„o fez anivers·rio
	if (dif_mes<0) then
		idade=cint(dif_ano)-1
	else
		idade=dif_ano
	end if
end function

Function toUPPER(texto)
    Dim str, i, termo, cont, quebra
        quebra = Replace(texto,".","")
        str = Split(quebra," ") ' criamos um vetor
        For i = LBound(str) to UBound(str)
            cont = Len(str(i))
                If ( cont = 1 ) Then
                    If ( isNumeric(str(i)) ) Then
                        termo = termo & str(i) & " "
                    Else
                        termo = termo & LCase(str(i)) & " "
                    End If			
                ' Se caso usar abreviaÁ„o EX adriano r souza Retorna R.
                ElseIf ( cont = 2 ) Then 
                    termo = termo & LCase(str(i)) & " "
                ElseIf ( cont > 2 ) Then
                    termo = termo & UCase(Mid(str(i),1,1)) & LCase(Mid(str(i),2))& " "
                End If
            Next
        toUPPER = Trim(termo)
End Function

'=======================
Dim x_Centavos, x_I, x_J, x_K, x_Numero, x_QtdCentenas, x_TotCentenas, x_TxtExtenso( 900 ), x_TxtMoeda( 6 ), x_ValCentena( 6 ), x_Valor, x_ValSoma

' Matrizes de textos
x_TxtMoeda( 1 ) = "rea"
x_TxtMoeda( 2 ) = "mil"
x_TxtMoeda( 3 ) = "milh"
x_TxtMoeda( 4 ) = "bilh"
x_TxtMoeda( 5 ) = "trilh"

x_TxtExtenso( 1 ) = "um"
x_TxtExtenso( 2 ) = "dois"
x_TxtExtenso( 3 ) = "tres"
x_TxtExtenso( 4 ) = "quatro"
x_TxtExtenso( 5 ) = "cinco"
x_TxtExtenso( 6 ) = "seis"
x_TxtExtenso( 7 ) = "sete"
x_TxtExtenso( 8 ) = "oito"
x_TxtExtenso( 9 ) = "nove"
x_TxtExtenso( 10 ) = "dez"
x_TxtExtenso( 11 ) = "onze"
x_TxtExtenso( 12 ) = "doze"
x_TxtExtenso( 13 ) = "treze"
x_TxtExtenso( 14 ) = "quatorze"
x_TxtExtenso( 15 ) = "quinze"
x_TxtExtenso( 16 ) = "dezesseis"
x_TxtExtenso( 17 ) = "dezessete"
x_TxtExtenso( 18 ) = "dezoito"
x_TxtExtenso( 19 ) = "dezenove"
x_TxtExtenso( 20 ) = "vinte"
x_TxtExtenso( 30 ) = "trinta"
x_TxtExtenso( 40 ) = "quarenta"
x_TxtExtenso( 50 ) = "cinquenta"
x_TxtExtenso( 60 ) = "sessenta"
x_TxtExtenso( 70 ) = "setenta"
x_TxtExtenso( 80 ) = "oitenta"
x_TxtExtenso( 90 ) = "noventa"
x_TxtExtenso( 100 ) = "cento"
x_TxtExtenso( 200 ) = "duzentos"
x_TxtExtenso( 300 ) = "trezentos"
x_TxtExtenso( 400 ) = "quatrocentos"
x_TxtExtenso( 500 ) = "quinhentos"
x_TxtExtenso( 600 ) = "seiscentos"
x_TxtExtenso( 700 ) = "setentos"
x_TxtExtenso( 800 ) = "oitocentos"
x_TxtExtenso( 900 ) = "novecentos"

' FunÁ„o Principal de Convers„o de Valores em Extenso
Function Extenso( x_Numero )
x_Numero = FormatNumber( x_Numero , 2 )
x_Centavos = right( x_Numero , 2 )
x_ValCentena( 0 ) = 0
x_QtdCentenas = int( ( len( x_Numero ) + 1 ) / 4 )

For x_I = 1 to x_QtdCentenas
x_ValCentena( x_I ) = ""
Next
'
x_I = 1
x_J = 1
For x_K = len( x_Numero ) - 3 to 1 step -1
x_ValCentena( x_J ) = mid( x_Numero , x_K , 1 ) & x_ValCentena( x_J )
if x_I / 3 = int( x_I / 3 ) then
x_J = x_J + 1
x_K = x_K - 1
end if
x_I = x_I + 1
next
x_TotCentenas = 0
Extenso = ""
For x_I = x_QtdCentenas to 1 step -1

x_TotCentenas = x_TotCentenas + int( x_ValCentena( x_I ) )

if int( x_ValCentena( x_I ) ) <> 0 or ( int( x_ValCentena( x_I ) ) = 0 and x_I = 1 )then
if int( x_ValCentena( x_I ) = 0 and int( x_ValCentena( x_I + 1 ) ) = 0 and x_I = 1 )then
Extenso = Extenso & ExtCentena( x_ValCentena( x_I ) , x_TotCentenas ) & " de " & x_TxtMoeda( x_I )
else
Extenso = Extenso & ExtCentena( x_ValCentena( x_I ) , x_TotCentenas ) & " " & x_TxtMoeda( x_I )
end if
if int( x_ValCentena( x_I ) ) <> 1 or ( x_I = 1 and x_TotCentenas <> 1 ) then
Select Case x_I
Case 1
Extenso = Extenso & "is"
Case 3, 4, 5
Extenso = Extenso & "ıes"
End Select
else
Select Case x_I
Case 1
Extenso = Extenso & "l"
Case 3, 4, 5
Extenso = Extenso & "„o"
End Select
end if
end if
if int( x_ValCentena( x_I - 1 ) ) = 0 then
Extenso = Extenso
else
if ( int( x_ValCentena( x_I + 1 ) ) = 0 and ( x_I + 1 ) <= x_QtdCentenas ) or ( x_I = 2 ) then
Extenso = Extenso & " e "
else
Extenso = Extenso & ", "
end if
end if
next

if x_Centavos > 0 then
if int( x_Centavos ) = 1 then
Extenso = Extenso & " e " & ExtDezena( x_Centavos ) & " centavo"
else
Extenso = Extenso & " e " & ExtDezena( x_Centavos ) & " centavos"
end if
end if
Extenso = UCase( Left( Extenso , 1 ) )&right( Extenso , len( Extenso ) - 1 )
End Function

function ExtDezena( x_Valor )
' Retorna o Valor por Extenso referente ‡ DEZENA recebida
ExtDezena = ""
if int( x_Valor ) > 0 then
if int( x_Valor ) < 20 then
ExtDezena = x_TxtExtenso( int( x_Valor ) )
else
ExtDezena = x_TxtExtenso( int( int( x_Valor ) / 10 ) * 10 )
if ( int( x_Valor ) / 10 ) - int( int( x_Valor ) / 10 ) <> 0 then
ExtDezena = ExtDezena & " e " & x_TxtExtenso( int( right( x_Valor , 1 ) ) )
end if
end if
end if
End Function

function ExtCentena( x_Valor, x_ValSoma )
ExtCentena = ""

if int( x_Valor ) > 0 then

if int( x_Valor ) = 100 then
ExtCentena = "cem"
else
if int( x_Valor ) < 20 then
if int( x_Valor ) = 1 then
If x_ValSoma - int( x_Valor ) = 0 then
ExtCentena = "hum"
else
ExtCentena = x_TxtExtenso( int( x_Valor ) )
end if
else
ExtCentena = x_TxtExtenso( int( x_Valor ) )
end if
else
if int( x_Valor ) < 100 then
ExtCentena = ExtDezena( right( x_Valor , 2 ) )
else
ExtCentena = x_TxtExtenso( int( int( x_Valor ) / 100 )*100 )
if ( int( x_Valor ) / 100 ) - int( int( x_Valor ) / 100 ) <> 0 then
ExtCentena = ExtCentena & " e " & ExtDezena( right( x_Valor , 2 ) )
end if
end if
end if
end if
end if
End Function

Function Mes_ext(numero)
Select Case numero
	case 1
	   mes_ext="JANEIRO"
	case 2
	   mes_ext="FEVEREIRO"
	case 3
	   mes_ext="MAR«O"
	case 4
	   mes_ext="ABRIL"
	case 5
	   mes_ext="MAIO"
	case 6
	   mes_ext="JUNHO"
	case 7
	   mes_ext="JULHO"
	case 8
	   mes_ext="AGOSTO"
	case 9
	   mes_ext="SETEMBRO"
	case 10
	   mes_ext="OUTUBRO"
	case 11
	   mes_ext="NOVEMBRO"
	case 12
	   mes_ext="DEZEMBRO"
end select
End Function

Function Tira_Acento(Palavra)
CAcento = "‡·‚„‰ËÈÍÎÏÌÓÔÚÛÙıˆ˘˙˚¸¿¡¬√ƒ»… ÀÃÕŒ“”‘’÷Ÿ⁄€‹Á«Ò—"
SAcento = "aaaaaeeeeiiiiooooouuuuAAAAAEEEEIIIOOOOOUUUUcCnN"
Texto = ""
If Palavra <> "" then
        For X = 1 To Len(Palavra)
               Letra = Mid(Palavra,X,1)
               Pos_Acento = InStr(CAcento,Letra)
               If Pos_Acento > 0 Then 
				  Letra = mid(SAcento,Pos_Acento,1) 
			   End if
               Texto = Texto & Letra
        Next
        Tira_Acento = Texto
End If
End Function

Function DiasdoMes(Mes,Ano)
  Select Case Mes
    Case 1,3,5,7,8,10,12: DiasdoMes = 31
    Case 4,6,9,11: DiasdoMes = 30
    Case Else
      If Ano Mod 4 = 0 And (Ano Mod 100 <> 0 Or Ano Mod 400 = 0) Then
         DiasdoMes = 29
      Else
         DiasdoMes = 28
      End If
  End Select
End Function

Function FormataNumero(numero)
	FormataNumero=replace(Right("00000000000000" + formatnumber(numero,2,-1,0,0),15),",",".")
End Function


'=========================================
'dia_m = day(now)
'
'mes = month(now)
'ano = year(now)
'hora = time()
function data_diasemana(data_s)
dia_s = weekday(data_s)
Select Case dia_s
Case "1"
 dia_s = "Domingo"
Case "2"
 dia_s = "Segunda-feira"
Case "3"
 dia_s = "TerÁa-feira"
Case "4"
 dia_s = "Quarta-feira"
Case "5"
 dia_s = "Quinta-feira"
Case "6"
 dia_s = "Sexta-feira"
Case "7"
 dia_s = "S·bado"
End Select

mes = month(data_s)
Select Case mes
Case "1"
 mes = "Janeiro"
Case "2"
 mes = "Fevereiro"
Case "3"
 mes = "MarÁo"
Case "4"
 mes = "Abril"
Case "5"
 mes = "Maio"
Case "6"
 mes = "Junho"
Case "7"
 mes = "Julho"
Case "8"
 mes = "Agosto"
Case "9"
 mes = "Setembro"
Case "10"
 mes = "Outubro"
Case "11"
 mes = "Novembro"
Case "12"
 mes = "Dezembro"
 Response.Write data_s & ", " & dia_s 
End Select

End Function
%>