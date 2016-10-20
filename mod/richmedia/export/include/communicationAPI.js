/*

Derni�re mise � jour : 10 / 08 / 2011
Par : Dominique DUPLAN
Auteur : Symetrix
Version : 1.0
Description : API de communication entre Flash et la page HTML

*/

/**
 * Fonction permettant de faire un setValue sur  le status du module
 *
@param : state : �tat du module (incomplete, complete, passed, failed)
 *
@return : Aucun
 */
function SetStatus (state)
{
		// on set la value
		SetValue("cmi.core.lesson_status",state);
		
		//on commit
		commitData();
		
		
}

/**
 * Fonction permettant d'envoyer une variable � l'aide de l'API SCORM
 *
@param : data : nom de la variable � mettre � jour (ex : cmi.core.score.raw pour le score) 
 *
@param : value : valeur � donner � la variable
 *
@return : Aucun
 */
function SetValue(data, value)
{
	var params = new Array(data,value)
	DoCommand("LMSSetValue", params );
}

/**
 * Fonction permettant de r�cup�rer une variable � l'aide de l'API SCORM. 
 * Il y a automatiquement stockage de cette variable en javascript pour la r�cup�rer via GetInitialValue(data)
 *
@param : data : nom de la variable � r�cup�rer.
 *
@return : true
 */
function GetValue(data)
{
	var params = new Array(data)
	var value = DoCommand("LMSGetValue", params );
	stockvalue = String(data).split(".").join("-"); 
	
	this[stockvalue] = value;
	return true;
}

/**
 * Fonction permettant de r�cup�rer la variable javascript stock�e via la fonction GetValue(data)
 * Pour fonctionner la variable doit �tre initialis�e.
 *
@param : data : nom de la variable � r�cup�rer.
 *
@return : Valeur de la variable javascript stock�e
 */
function GetInitialValue(data)
{
	stockvalue = String(data).split(".").join("-"); 
	return this[stockvalue];

}

/**
 * Fonction permettant d'envoyer au LMS le temps de session.
 *
@param : Aucun
 *
@return : Aucun
 */
function SetSessionTime ()
{
		var d = new Date();
		CurrentTime = d.getTime();
		
		var TimeElapsed = CurrentTime - StartSessionTime;
	
		var StrTime = GetEllapsedTime(TimeElapsed/1000);
		
		// on set la value
		SetValue("cmi.core.session_time",StrTime);
		
		//on commit
		commitData();
}

/**
 * Fonction permettant de faire un setValue sur  le suspend data
 *
@param : stringData : valeur du suspend_data
 *
@return : Aucun
 */
function SetSuspendData (stringData)
{
	//on set la value
	SetValue("cmi.suspend_data",stringData);
	
	//on commit
	commitData();
}

/**
 * Fonction permettant de faire un commit au LMS
 *
@param : Aucun
 *
@return : Aucun
 */
function commitData()
{
	var params = new Array("")
	DoCommand("LMSCommit",params)
}

/**
 * Fonction permettant de faire un finish au LMS
 *
@param : Aucun
 *
@return : Aucun
 */
function finishLMS()
{
	var params = new Array("")
	DoCommand("LMSFinish",params)
}

/**
 * Fonction permettant de faire un setValue sur le possitionnement du dernier ecran consult�
 *
@param : stringLocation : chaine de caract�re ASCII
 *
@return : Aucun
 */
function SetLessonLocation (stringLocation)
{
	//on set la value
	SetValue("cmi.lesson_location",stringLocation);
	
	//on commit
	commitData();
}

/**
 * Fonction permettant de faire un setValue sur le score de la le�on
 *
@param : score : de 0 � 100
 *
@return : Aucun
 */
function SetScore (score)
{
	//on set la value
	SetValue("cmi.core.score.raw",score);
	
	//on commit
	commitData();
}