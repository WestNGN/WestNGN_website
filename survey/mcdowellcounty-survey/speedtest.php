<script type="text/javascript" src="speedtest.js"></script>
<script type="text/javascript">

//INITIALIZE SPEEDTEST
var s=new Speedtest(); //create speedtest object

var myDLResult;  
var myULResult; 

var bestUpload=0;
var bestDownload=0;
var testCounter=0;

s.onupdate=function(data){ //callback to update data in UI
    I("ip").textContent=data.clientIp;
    I("dlText").textContent=(data.testState==1&&data.dlStatus==0)?"...":data.dlStatus;
    I("ulText").textContent=(data.testState==3&&data.ulStatus==0)?"...":data.ulStatus;

	myDLResult = data.dlStatus;
    myULResult = data.ulStatus;
    //^saves the upload and download data into variables with bigger scope.
    
    I("pingText").textContent=data.pingStatus;
 //   I("jitText").textContent=data.jitterStatus;
 //		commented out so jitter not shown.
    var prog=(Number(data.dlProgress)*2+Number(data.ulProgress)*2+Number(data.pingProgress))/5;
    I("progress").style.width=(100*prog)+"%";
}
s.onend=function(aborted){ //callback for test ended/aborted
    I("startStopBtn").className=""; //show start button again
    if(aborted){ //if the test was aborted, clear the UI and prepare for new test
		initUI();
    }
    else{
    	console.log("Test has Concluded");
    	testCounter++;
    	console.log("test count: "+testCounter); 
   		console.log("most recent download result is: "+ Math.round(myDLResult));
    	console.log("most recent upload result is: "+ Math.round(myULResult));    
    	if(Math.round(myDLResult) > bestDownload){
			bestDownload = Math.round(myDLResult);
        }
        if(Math.round(myULResult) > bestUpload){ 
			bestUpload = Math.round(myULResult);
        }
        console.log("Best down so far: "+bestDownload);     
        console.log("Best up so far: "+bestUpload);
        window.parent.getResults(bestDownload, bestUpload);        
    }
}

function startStop(){ //start/stop button pressed
	if(s.getState()==3){
		//speedtest is running, abort
		s.abort();
	}else{
		//test is not running, begin
		s.start();
		I("startStopBtn").className="running";
	}
}

//function to (re)initialize UI
function initUI(){
	I("dlText").textContent="";
	I("ulText").textContent="";
	I("pingText").textContent="";
	I("ip").textContent="";
}

function I(id){return document.getElementById(id);}
</script>

<style type="text/css">
	#speedtest{		
		background:#FFFFFF;
		border:none; 
		color:#202020;
		font-family:"Roboto",sans-serif;
	    margin:0;	    
		padding:0; 
		text-align:center;
	}
	#startStopBtn{
        font-size: 1.4em;
		display:inline-block;
		margin:0 auto;
		/*
		color: #06284F;
		background-color:rgba(0,0,0,0);
		border:0.2em solid #6060FF; 
		*/
		color:#FFFFFF;
		background-color: #2AB84C; 
		border: .2em solid #2AB84C;
		border-radius:0.3em;
		transition:all 0.3s;
		box-sizing:border-box;
		width:8em; height:3em;
		line-height:2.7em;
		cursor:pointer;
		box-shadow: 0 0 0 rgba(0,0,0,0.1), inset 0 0 0 rgba(0,0,0,0.1);
	}
	#startStopBtn:hover{
		box-shadow: 0 0 2em rgba(0,0,0,0.1), inset 0 0 1em rgba(0,0,0,0.1);
	}
	#startStopBtn.running{
		background-color:#FF3030;
		border-color:#FF6060;
		color:#FFFFFF;
	}
	#startStopBtn:before{
		content:"Start";
	}
	#startStopBtn.running:before{
		content:"Abort";
	}
	#test{
		margin-top:2em;
	}
	div.testArea{
		display:inline-block;
		width:14em;
		height:9em;
		position:relative;
		box-sizing:border-box;
	}
	div.testName{
		position:absolute;
		top:0.1em; left:0;
		width:100%;
		font-size:1.4em;
		z-index:9;
	}
	div.meterText{
		position:absolute;
		bottom:1.5em; left:0;
		width:100%;
		font-size:2.5em;
		z-index:9;
	}
	#dlText{
		color:#6060AA;
	}
	#ulText{
		color:#309030;
	}
	#pingText,#jitText{
		color:#AA6060;
	}
	div.meterText:empty:before{
		color:#505050 !important;
		content:"0.00";
	}
	div.unit{
		position:absolute;
		bottom:2em; left:0;
		width:100%;
		z-index:9;
	}
	div.testGroup{
		display:inline-block;
	}
	@media all and (max-width:65em){
		#speedtest{
			font-size:1.5vw;
		}
	}
	@media all and (max-width:40em){
		#speedtest{
			font-size:0.8em;
		}
		div.testGroup{
			display:block;
			margin: 0 auto;
		}
	}
	#progressBar{
		width:90%;
		height:0.3em;
		background-color:#EEEEEE;
		position:relative;
		display:block;
		margin:0 auto;
		margin-bottom:2em;
	}
	#progress{
		position:absolute;
		top:0; left:0;
		height:100%;
		width:0%;
		transition: width 2s;
		background-color:#90BBFF;
	}

</style>
<div id=speedtest>
	<h3>Speed Test</h3>
	<div id="startStopBtn" onclick="startStop()"></div>
	<div id="test">
    	<div id="progressBar"><div id="progress"></div></div>
		<div class="testGroup">
			<div class="testArea">
				<div class="testName">Download</div>
				<div id="dlText" class="meterText"></div>
				<div class="unit">Mbps</div>
			</div>
			<div class="testArea">
				<div class="testName">Upload</div>
				<div id="ulText" class="meterText"></div>
				<div class="unit">Mbps</div>
			</div>
		</div>
		<div class="testGroup">
			<div class="testArea">
				<div class="testName">Latency</div>
				<div id="pingText" class="meterText"></div>
				<div class="unit">ms</div>
			</div>
		</div>
		<div id="ipArea">
			IP Address: <span id="ip"></span>
		</div>
	</div>
	Speed Test code provided by Libre <a href="https://github.com/librespeed/speedtest">(Source code)</a>
	<script type="text/javascript">
    initUI();
	//console.log(dlStatus);
	</script>
</div>
