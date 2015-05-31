<?php

	function get_url(){

		if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){
			$url = "http://site.com.br/";
		}else{
			$url = "http://localhost/site/";
		}
		echo $url;
	}


	/* Conexão com BD */
	function conectar(){

		if ($_SERVER['REMOTE_ADDR'] != "127.0.0.1"){
			$server = "localhost remote";
			$user = "user";
			$senha = "password";
			$banco = "database";
		} else {
			$server = "localhost";
			$user = "user";
			$senha = "password";
			$banco = "database";
		}
		// termina conexão com o banco

		$conexao = mysql_connect($server,$user,$senha);
		if(!$conexao){
			die('Erro de conexão: ' . mysql_error());
		}

		$db = mysql_select_db($banco,$conexao);
		if(!$db){
			die('Erro de acesso ao banco: ' . mysql_error());
		}

		mysql_query("SET NAMES 'utf8'");
		mysql_query('SET character_set_connection=utf8');
		mysql_query('SET character_set_client=utf8');
		mysql_query('SET character_set_results=utf8');
	}//conectar




	function desconectar(){
	 	mysql_close();
	}//desconectar




	/* CARREGAR ELEMENTOS */
	global $web_page_title;
	function get_head($title = null){
		$GLOBALS['web_page_title'] = $title;
		require_once("head.php");
	}
	function get_header(){
		require_once("header.php");
	}
	function get_footer(){
		require_once("footer.php");
	}



	//FUNÇÃO PARA VERIFICAR FORMATAÇÃO DO EMAIL
	function verificar_email($email){

		$mail_correcto = 0;
		//verifico umas coisas
		if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
			if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
			//vejo se tem caracter .
				if (substr_count($email,".")>= 1){
					//obtenho a terminação do dominio
					$term_dom = substr(strrchr ($email, '.'),1);
					//verifico que a terminação do dominio seja correcta
					if (strlen($term_dom) > 1 && strlen($term_dom) < 5 && (!strstr($term_dom,"@")) ){
						//verifico que o de antes do dominio seja correcto
						$antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
						$caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
						if ($caracter_ult != "@" && $caracter_ult != "."){
						   $mail_correcto = 1;
						}
					}
			   }
		   }
		}

		if ($mail_correcto)
		 	return 1;
		else
		 	return 0;
	}//verificar email







	function data($data,$formato=12){

		$hora = $formato == 12 ? "h" : "H";

		$am_pm = (date("H",strtotime($data)) < 12) ? " AM" : " PM";

		$am_pm = $formato == 24 ? "" : $am_pm;

		return date("d/m/Y",strtotime($data));
	}












	function dataTime($data,$formato=24){

		$hora = $formato == 12 ? "h" : "H";

		$am_pm = (date("H",strtotime($data)) < 12) ? " AM" : " PM";

		$am_pm = $formato == 24 ? "" : $am_pm;

		if(date('d/m/Y', strtotime($data)) == date('d/m/Y')){
			return "Hoje às ".date("$hora:i",strtotime($data)).$am_pm;
		}
		else if(date('m/Y', strtotime($data)) == date('m/Y') && date("d", strtotime($data)) == date("d")-1){
			return "Ontem às ".date("$hora:i",strtotime($data)).$am_pm;
		}
		else if(date('m/Y', strtotime($data)) == date('m/Y') && date("d", strtotime($data)) == date("d")+1){
			return "Amanhã às ".date("$hora:i",strtotime($data)).$am_pm;
		}
		else{
			return date("d/m/Y",strtotime($data));
		}
		return date("d/m/Y",strtotime($data));
	}
	//exemplo de uso
	/*
	echo data("2011-11-10 16:59:00"); // 10/11/2011
	echo data("2011-11-14 00:00:01"); // Hoje às 00:00
	echo data("2011-11-13 15:00:00",24); // Ontem às 03:00 PM
	echo data("2011-11-15 12:20:00"); // Amanha às 12:20
	*/







	function data_info(){

		$dia = date("j")-1;
		$hora = date("H")-3;
		$minuto = date("i");
		$segundo = date("s");

		$semana = array(0 => "Domingo",1 => "Segunda", 2 => "Terça", 3 => "Quarta",  4 => "Quinta", 5 => "Sexta",  6 => "Sábado");
		$mes = array(1 => "Janeiro",  2 => "Fevereiro",  3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro",  11 => "Novembro", 12 => "Dezembro");

		$ano = date("Y");
		$data_completa = date("d/m/y");
		$hora_completa = $hora.":".$minuto.":".$segundo;
		$misc = $semana[date("w")].", ".date("j")." de ".$mes[date("n")]." de ".date("Y");
	}
	//exemplo de uso do procedimento data_info()
	/*
	data_info();
	echo $misc; // Segunda, 14 de novembro de 2011
	echo $hora_completa; //23:54:20
	*/




	function data_db($data_nasc) {
		if (strstr($data_nasc, "/")) {
			  $data_array = explode ("/", $data_nasc);
			  return $data_array[2] . "-". $data_array[1] . "-" . $data_array[0];
		} else {
			return null;
		}
	}



	function dateTime_toDate($my_datetime) {
		//entrada 2013-12-30 12:00:45
		$timestamp = strtotime($my_datetime);
		return date("Y-m-d", $timestamp);
		//saida 2013-12-30
	}








	function truncate($str, $len, $etc='') {

		$end = array(' ', '.', ',', ';', ':', '!', '?');

		if (strlen($str) <= $len)
			return $str;

		if (!in_array($str{$len - 1}, $end) && !in_array($str{$len}, $end))
			while (--$len && !in_array($str{$len - 1}, $end));

		return rtrim(substr($str, 0, $len)).$etc;
	}
	/*

	$str = 'Estrelas pequenas e com pouco brilho, conhecidas como anãs vermelhas, são muito mais comuns do que se imaginava.';
	substr($str, 0, 50);
	>> Estrelas pequenas e com pouco brilho, conhecidas co

	truncate($str, 50);
	>> Estrelas pequenas e com pouco brilho, conhecidas

	truncate($str, 50, '...');
	>> Estrelas pequenas e com pouco brilho, conhecidas...
	*/












    function jf_form_actions(){

        $params = func_get_args();

        foreach ($params as $name) {

            if (isset($_POST[$name])) {
                unset($_POST[$name]);
                return $name;
            }
        }
    }
    /*
    switch (jf_form_actions('arg1', 'arg2', ..., 'argN')){
        case 'arg1':
        break;

        case 'arg2':
        break;

        ...

        case 'argN':
        break
    }
    */













	function getPost($POST) {

		$k = null;

		if (!get_magic_quotes_gpc()) {
			//caso não esteja ativo a função de escapar string
			foreach( $POST as $campo => $vlr){
				if(is_array($vlr)){
					$$campo = $vlr;
					if($k == null){$k = 0;}
					for($i=0; $i < count($vlr); $i++){
						$$campo[$i] = addslashes($vlr[$i]);
						if($i>$k){$k=$i;}
					}
				}else{
					$$campo = addslashes($vlr);
				}
			}

		} else {

			foreach( $POST as $campo => $vlr){
				if(is_array($vlr)){
					$$campo = $vlr;
					if($k == null){$k = 0;}
					for($i=0; $i < count($vlr); $i++){
						$$campo[$i] = $vlr[$i];
						if($i>$k){$k=$i;}
					}
				}else{
					$$campo = $vlr;
				}
			}
		}
	}







	/* ENCURTAR URL */
	function encurtar_url($url){
	    $url = trim($url);
	    $url = urlencode($url);
	    $shorted_url = file_get_contents('http://migre.me/api.txt?url='.$url );
	    return $shorted_url;
	}



    function getIp() {

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else{

            $ip = $_SERVER['REMOTE_ADDR'];
        }

        return $ip;
    }




?>