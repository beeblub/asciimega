//######################################################
//# Author: tomatenbrei, Contact: tomadroide@gmail.com #
//######################################################

//-----------------------
//TO-DO:
//skip(frameid)
//-> Problems: get the Colordata/Timedata from the last frames to refresh the color
//-----------------------

//Important divs in document:

//animation_wrapper
//-> for animation
//animation_info
//-> for info (gets displayed when update.. is on.)

//Important methods:

//call load_animation(animationdata,update_animationinfo_in)
//1st argument: string, data containing the header. 2nd argument: boolean, whether to show update information

//call "play()" to play.
//call "stop()" to pause
//call "reset()" to reset to frame 1 (e.g. when the animation finished playing)

//File Header Format:

//Header\n
//Width\n
//NUMB\n
//Heigth\n
//NUMB\n
//Frametime\n
//NUMB\n
//Autoloop\n
//true||false\n
//ColTimDat\n
//FRAME;TIME.BACKGROUND,FOREGROUND,||FRAME;TIME.||FRAME;BACKGROUND,FOREGROUND,\n
//Header End\n

//ColTimDat Frames start with Frame 0, not with Frame 1.

//Frametime just works when ColTimeDat does not exist. -> First timer-command overrides the Frametime argument.
//-> When you want to use ColTimeDat, define a frametime for frame number 0.
//-> same with colors.

var donotallowplay = false;

var movx = null;
var movy = null;

var autoloop = null;

var framenum = 1;

var currframe = 0;
var frametime = null;

var timeouthandler = null;

var movdat = null;

var update_animationinfo = false;
var update_animationinfo_progress = false;

var duration = 0;
var duration_progress = 0;

var actualplaymode = 0;
//0 = nothing
//1 = play
//2 = pause
function play()
{
	update();
	actualplaymode = 1;
}
function stop()
{
	if(timeouthandler)
		clearTimeout(timeouthandler);
	timeouthandler = null;

	actualplaymode = 2;
}
function reset()
{
	if(timeouthandler)
		clearTimeout(timeouthandler);
	timeouthandler = null;
	currframe = 0;

	coldat_count = 0;
	timdat_count = 0;
	if(coldat_frames.length > 0)	
		coldat_next = coldat_frames[0];
	if(timdat_frames.length > 0)
		timdat_next = timdat_frames[0];

	if(actualplaymode == 2)
	{
		play();
		stop();
	}
	else
	{
		play();
	}
}

var coldat_next = null;
var coldat_count = 0;

var timdat_next = null;
var timdat_count = 0;

var tmpframetime = 0;

function progress_show_end()
{
	duration_progress = duration;
	update_progress();
}
function changefontsize(fsize)
{
	var wrapper = document.getElementById("animation_wrapper");
	wrapper.style.fontSize = fsize;

	changefontsize_container(fsize);
	relocate_container();
}
function update()
{
	if(timeouthandler)
		clearTimeout(timeouthandler);

	var a = performance.now();

	if(currframe == 0)
	{
		duration_progress = 0;
		update_progress();
	}
	else
	{
		duration_progress += frametime;
		update_progress();
	}	
	if(coldat_next != null)
	{
		
		if(coldat_next == currframe)
		{
			if(coldat_count < coldat_frames.length-1)
			{
				set_wrapper_style(coldat_foreground[coldat_count],coldat_background[coldat_count]);
				coldat_count++;
				coldat_next = coldat_frames[coldat_count];
			}
			else
			{
				set_wrapper_style(coldat_foreground[coldat_count],coldat_background[coldat_count]);
				coldat_count = 0;
				coldat_next = coldat_frames[coldat_count];
			}
		}
	}
	if(timdat_next != null)
	{
		if(timdat_next == currframe)
		{
			if(timdat_count < timdat_frames.length-1)
			{
				frametime = timdat_time[timdat_count];
				timdat_count++;
				timdat_next = timdat_frames[timdat_count];
			}
			else
			{
				frametime = timdat_time[timdat_count];
				timdat_count = 0;
				timdat_next = timdat_frames[timdat_count];
			}
		}
	}
	
	var timfac = loadframe(currframe);
	update_information();
	var b = performance.now();
	var ms = (b - a);	

	if(currframe < framenum-1)	
	{
		currframe++;
		timeouthandler = setTimeout(update,timfac*frametime-ms);
	}
	else
	{
		if(autoloop == true)
		{
			currframe = 0;
			timeouthandler = setTimeout(update,timfac*frametime-ms);
		}
		else
		{
			timeouthandler = setTimeout(progress_show_end,frametime-ms);
			//The animation ends here
		}
	}


}
function changefontsize_container(fsize)
{
	var wrapper = document.getElementById("animation_testsize");
	if(wrapper)
		wrapper.style.fontSize = fsize;
}
function relocate_container()
{
	var resizeelement = document.getElementById("animation_testsize");
	if(resizeelement)
	{
		//Content of resize element equals length
		while(resizeelement.firstChild)
	    		resizeelement.removeChild(resizeelement.firstChild );
		
		var tmpstring = "";
		for(var i = 0;i<movx;i++)
		{
			tmpstring+="X";
		}
		//resizeelement.innerHTML = tmpstring;
		var div = document.createElement('div');
		div.style.whiteSpace = 'pre';
		div.appendChild( document.createTextNode( tmpstring ) );
		resizeelement.appendChild( div );
		
		var tmpwidth = (resizeelement.clientWidth + 1) + "px";
		var container = document.getElementById("animation_win_container");
		container.style.width = tmpwidth;
	}
}

function load_animation(animationdata,update_animationinfo_in,update_animationinfo_progress_in)
{
	movdat = animationdata;
   	readheader();
	update_animationinfo = update_animationinfo_in;
	update_animationinfo_progress = update_animationinfo_progress_in;

	relocate_container();
}

var coldat_frames = [];
var coldat_foreground = [];
var coldat_background = [];

var timdat_frames = [];
var timdat_time = [];


function readcoltimdat(buffer)
{
	var tmpchar = null;
	var tmpframe = 0;
	var tmpbuffer = "";
	var tmpcolmode = 0;
	var tmpcol = null;
	for(var i = 0;i<buffer.length;i++)
	{
		
				tmpchar = buffer.charAt(i);
				if(tmpchar == ';')
				{
					tmpframe = parseInt(tmpbuffer);
					tmpbuffer = "";
					tmpcolmode = 0;
					tmpcol = null;
				}
				else if(tmpchar == ':')
				{
					if(tmpcolmode == 0)
					{
						tmpcolmode = 1;
						tmpcol = tmpbuffer;
						tmpbuffer = "";
					}
					else
					{
						if(tmpcol != null)
						{
							//tmpbuffer ist second frame
							coldat_frames[coldat_frames.length] = tmpframe;
							coldat_foreground[coldat_foreground.length] = tmpbuffer;
							coldat_background[coldat_background.length] = tmpcol;

							tmpbuffer = "";
						}
					}
				}
				else if(tmpchar == '.')
				{
					timdat_frames[timdat_frames.length] = tmpframe;
					timdat_time[timdat_time.length] = parseInt(tmpbuffer);
					tmpbuffer = "";
				}
				else
				{
					tmpbuffer+= buffer.charAt(i);
				}
				
				
	}
	if(tmpbuffer != "")
	{
		return false;
	}

	if(coldat_frames.length > 0)	
		coldat_next = coldat_frames[0];
	if(timdat_frames.length > 0)
		timdat_next = timdat_frames[0];
		
	return true;
}
function calculate_duration()
{
	if(timdat_frames.length > 0)
	{
		var tmptime = 0;
		var currtimedat = 0;
		var currtime = frametime;
		
		for(var i = 0;i<framenum;i++)
		{
			if(i == timdat_frames[currtimedat])
			{
				currtime = timdat_time[currtimedat];
				if(currtimedat < timdat_frames.length)
				{
					currtimedat++;
				}
			}
			tmptime += currtime;
		}
		duration = tmptime;
		//alert("Dynamic duration: "+duration);

	}
	else
	{
		duration = framenum*frametime;
		//alert("Fixed duration: "+duration);
	}
}

function readheader()
{
	var h = movdat[0];
	if(h == "Header")
	{
		var buffer = "NONE";
		var indx = 1;
		var crntmode = 0;


		var width = 0;
		var heigth = 0;
	
		buffer = movdat[indx];
		var notcorrect = false;
		while(buffer != "Header End")
		{
			if(notcorrect)
				break;
			if(crntmode == 0)
			{
				switch(buffer) {
				    case "Width":
					{crntmode = 1;break;}
					
				    case "Height":
					{crntmode = 2;break;}
	
				    case "Frametime":
					{crntmode = 3;break;}

				    case "Autoloop":
					{crntmode = 4;break;}

				    case "ColTimDat":
					{crntmode = 5;break;}
					
				    default:
					break;
				} 
			}
			else
			{
				switch(crntmode) {
				    case 1:
					{movx = parseInt(buffer) || null;break;}
					
				    case 2:
					{movy = parseInt(buffer) || null;;break;}

				    case 3:
					{frametime = parseInt(buffer) || null;;break;}
				
				    case 4:
					{if(buffer == "true"){autoloop = true;}else if(buffer == "false"){autoloop = false;}else {notcorrect = true;}break;}

				    case 5:
					{if(readcoltimdat(buffer) == false){notcorrect = true;}break;}

				    default:
					break;}
				crntmode = 0;
			}
			
			indx++;
			if(indx < movdat.length)
				buffer = movdat[indx];
			else
			{
				
				notcorrect = true;
				break;
			}
		}
		indx++;

		if(movx == null || movy == null || frametime == null || autoloop == null)
			notcorrect = true;


		if(notcorrect)
			{
				alert("Header not correct");
				framenum = 0;
			}
		else
		{
			framenum = (movdat.length - indx-1)/movy;
			movdat.splice(0,indx);
	
			set_wrapper_style("black","white");
			calculate_duration();
		}
	}
}
function update_information()
{
	if(update_animationinfo)
	{
		var wrapper = document.getElementById("animation_info");
		wrapper.innerHTML = movx+"x"+movy +" ("+frametime+"ms, "+(duration)/1000+"s animation) - Frame "+ (currframe+1) + " of " + framenum + " ("+(((currframe+1)/framenum)*100)+"%) "+Math.round((duration_progress/duration)*100);
	}
}
function update_progress()
{
	if(update_animationinfo_progress)
	{
		fill_with_feedback("progAnimation",Math.round((duration_progress/duration)*100));
	}
}
function set_wrapper_style(col_foreground,col_background)
{
	var wrapper = document.getElementById("animation_wrapper");
	wrapper.style.backgroundColor = col_background;
	wrapper.style.color = col_foreground;

	//for changing whole background color..
	var allcontainer = document.getElementById("animation_win");
	if(allcontainer)
	{
		document.getElementById("animation_win").style.backgroundColor = col_background;
	}
}

function loadframe(frameid)
{
	var wrapper = document.getElementById("animation_wrapper");
	while(wrapper.firstChild)
	    	wrapper.removeChild(wrapper.firstChild );
	
	var xc = frameid * movy;

	var retint = parseInt(movdat[xc]) || null;
	
	
	var startat = 0;
	if(retint != null)
		startat = 1;
	else
		retint = 1;

	if(retint <= 0)
		retint = 1;
	
	for(var i = startat;i<movy;i++)
	{
		var lineText = movdat[xc+i];

		
		if( !lineText || lineText.length < 1 )
			lineText = ' ';

		var div = document.createElement('div');
		div.style.whiteSpace = 'pre';
		div.appendChild( document.createTextNode( lineText ) );

		wrapper.appendChild( div );
	}
	return retint;
}

//1 = show stats, 2 = do progress bar
function animation_from_url(url,bool1,bool2)
{
	var movdat = null;
	var xmlhttp = null;
	if (window.XMLHttpRequest)
	  {
	  xmlhttp=new XMLHttpRequest();
	  }
	else
	  {
	    alert("You cannot use this application. Get IE7+, Firefox, Chrome, Opera or Safari.");
	  }
	xmlhttp.open("GET",url,false);
	xmlhttp.send();

	movdat = xmlhttp.responseText;

	movdat = movdat.split('\\n');
	load_animation(movdat,bool1,bool2);
	var wrapper = document.getElementById("animation_wrapper");
	var str = document.getElementById("animation_wrapper_str");
	str.innerHTML = "Finished downloading. Click to play.";
	str.style.color = "black";
}

//no cross orgin..
var docurl = document.URL;
var docs = 0;
for(var i = docurl.length;i > 0;i--)
{
	if(docurl.charAt(i) == '/')
	{
		docs = i;
		break;
	}	
}
var docroot = "";
for(var i = 0;i < docs;i++)
{
	docroot += docurl.charAt(i);
}
var adrs = docroot+"/files/";


//basic. Show stats but no bar.
function afu1(filename)
{
	animation_from_url(adrs+filename,true,false);
}
function afu2(filename)
{
	animation_from_url(adrs+filename,false,true);
}

//---site specific js
function fill_with_feedback(div_id, percentage)
{
	var thediv = document.getElementById(div_id);
	var ratio = 1/5;
	var height = 20;
	thediv.innerHTML = "";
	thediv.style = "position:relative;width:100%;height:"+height+"px;background-color:gray;";
	thediv.innerHTML += 
				"<div style='z-index:1;position:absolute;top:0;left:0;background-color:#cc1111;height:"+height+"px;width:"+percentage+"%;overflow:hidden;'>"+
				"</div>";
}
function supports_html5_storage() {
	  try {
	    return 'localStorage' in window && window['localStorage'] !== null;
	  } catch (e) {
	    return false;
	  }
	}
function animation_from_local_storeage()
{
	if (supports_html5_storage()) {
	  var movdat_tmp = window.localStorage.getItem("animationplain");
	  var movdat = movdat_tmp.split('\\n').slice();
          load_animation(movdat,false,true);
	} else {
		alert("No html5 storeage support!");
	}	
}
function afls()
{
	animation_from_local_storeage();
}


function clearstart()
{
	document.getElementById('display_preplay').style.display = 'none';
	document.getElementById('animation_wrapper_str').innerHTML ='';
	play();
}

