<!doctype html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>AIR FINDER HASHTAG</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid justify-content-md-center">
	<div class="row" style="position: fixed;width: 100%;z-index: 100;background-color: whitesmoke;">
		<div class="col-4">
			<div class="col-12 center-block text-center">
				<h1 class="text-center"><i class="fas fa-thumbtack"></i> HASHTAG FINDER </h1>
			</div>
			<div class="input-group mb-3">
			  <input id="hastag" type="text" class="form-control" placeholder="insert tag without #" aria-label="insert tag without #" aria-describedby="basic-addon2">
			  <div class="input-group-append">
				<button id="cerca-button" class="btn btn-outline-secondary" type="button" onclick=cerca()>Search <i class="fas fa-search"></i></button>				
			  </div>
			</div>
			<div class="col-12 center-block text-center" id="related"></div>
		</div>
		<div class="col-6">
			</br>
			<textarea readonly id="tags-list" class="form-control" rows="3"></textarea>
			<p><span id="tag-counter">0</span> of 30</p>
		</div>
		<div class="col-2">
			<br>
			<button id="cerca-button" class="btn btn-outline-secondary" type="button" onclick=copyToClip()>Copy <i class="far fa-copy"></i></button>
			<button id="cerca-button" class="btn btn-outline-secondary" type="button" onclick=clean()>Clean <i class="far fa-trash-alt"></i></button>
			
		</div>
	</div>
	<div class="row" style="padding-top:10em">
		<div class="col">
			<table class="table table-sm table-bordered table-hover">
				<thead>
					<tr>
						<th colspan="10" class="table-dark text-center">HASHTAG <span id="tot"></span><span id="tot_parz"></span></th>
					</tr>
					<tr>
						<th colspan="2" class="small text-center"></th>
						<th colspan="3" class="table-warning small text-center"><b>RECENT POST<b></th>
						<th colspan="3" class="table-success small text-center"><b>POPULAR POST<b></th>
					</tr>
					<tr>
						<th class="small text-center"><i>HASHTAG<i></th>
						<th class="small text-center"><i>TOT POST<i></th>
						<th class="table-warning small text-center"><i>AVG LIKE<i></th>
						<th class="table-warning small text-center"><i>AVG COMMENT<i></th>
						<th class="table-warning small text-center"><i>AVG AGE<i></th>
						<th class="table-success small text-center"><i>AVG LIKE<i></th>
						<th class="table-success small text-center"><i>AVG COMMENT<i></th>
						<th class="table-success small text-center"><i>AVG AGE<i></th>
					</tr>
				</thead>
				<tbody id="tagList"></tbody>
			</table>
		</div>
	</div>
</div>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script>
// Execute a function when the user releases a key on the keyboard
document.getElementById("hastag").addEventListener("keyup", function(event) {
  // Cancel the default action, if needed
  event.preventDefault();
  // Number 13 is the "Enter" key on the keyboard
  if (event.keyCode === 13) {
    // Trigger the button element with a click
    document.getElementById("cerca-button").click();
  }
});

var clean = function(){
	document.getElementById("tags-list").value = ""
	document.getElementById("tag-counter").innerHTML = 0
	document.getElementById('tot_parz').innerHTML = null
	document.getElementById('tot').innerHTML = null
}

var cerca = function(el){
	relatedCall()
}

function timeDifference(current, previous) {

    var msPerMinute = 60 * 1000;
    var msPerHour = msPerMinute * 60;
    var msPerDay = msPerHour * 24;
    var msPerMonth = msPerDay * 30;
    var msPerYear = msPerDay * 365;

    var elapsed = current - previous;

    if (elapsed < msPerMinute) {
         return Math.round(elapsed/1000) + ' sec ago';   
    }

    else if (elapsed < msPerHour) {
         return Math.round(elapsed/msPerMinute) + ' min ago';   
    }

    else if (elapsed < msPerDay ) {
         return Math.round(elapsed/msPerHour ) + ' h ago';   
    }

    else if (elapsed < msPerMonth) {
        return 'approximately ' + Math.round(elapsed/msPerDay) + ' d ago';   
    }

    else if (elapsed < msPerYear) {
        return 'approximately ' + Math.round(elapsed/msPerMonth) + ' months ago';   
    }

    else {
        return 'approximately ' + Math.round(elapsed/msPerYear ) + ' years ago';   
    }
}

function appendRow(el){
	var now = (new Date).getTime();
	var newTR = document.createElement('tr');
	newTR.setAttribute("id", "id-"+el.tag_name);
	newTR.setAttribute("onclick", "addText(\"#"+el.tag_name+"\")");
	newTR.setAttribute("style","cursor: pointer;");
	
	var newel = document.createElement('td');
	newel.innerHTML = "<a target='_blank' href='https://www.instagram.com/explore/tags/" +el.tag_name+"'>#"+el.tag_name+"</a>"
	newel.classList.add("text-center");
	newTR.appendChild(newel);
	
	var newel2 = document.createElement('td');
	newel2.innerHTML = el.stats.media_total_count.toLocaleString()
	newel2.classList.add("text-center");
	newTR.appendChild(newel2);
	
	var newel3 = document.createElement('td');
	newel3.innerHTML = el.stats.likeTotalAvg.toLocaleString()
	newel3.classList.add("text-center");
	newTR.appendChild(newel3);

	var newel4 = document.createElement('td');
	newel4.innerHTML = el.stats.commentTotalAvg.toLocaleString()
	newel4.classList.add("text-center");
	newTR.appendChild(newel4);
	
	var newel5 = document.createElement('td');
	newel5.innerHTML = timeDifference(now/1000, el.stats.timeTotalAvg)
	newel5.classList.add("text-center");
	newTR.appendChild(newel5);	
	
	var newel6 = document.createElement('td');
	newel6.innerHTML = el.stats.likeTotalAvg.toLocaleString()
	newel6.classList.add("text-center");
	newTR.appendChild(newel6);

	var newel7 = document.createElement('td');
	newel7.innerHTML = el.stats.commentTotalAvg.toLocaleString()
	newel7.classList.add("text-center");
	newTR.appendChild(newel7);
	
	var newel8 = document.createElement('td');
	newel8.innerHTML = timeDifference(now/1000, el.stats.timeTotalAvg)
	newel8.classList.add("text-center");
	newTR.appendChild(newel8);
	
	document.getElementById("tagList").appendChild(newTR);
	document.getElementById('tot_parz').innerHTML = parseInt(document.getElementById('tot_parz').innerHTML) + 1
}

function fillCall(tag_list_el)
{
	if (tag_list_el){
		let xhr = typeof XMLHttpRequest != 'undefined'
			? new XMLHttpRequest()
			: new ActiveXObject('Microsoft.XMLHTTP');
			
		xhr.onreadystatechange = function() {
			if (xhr.readyState == 4) {
				status = xhr.status;
				if (status == 200) {
					var resp = xhr.response;
					if(resp) {
						resp.forEach(function(entry) {
							appendRow(entry);
						});
					}
				} else {
					console.error("fillCall status: "+ status)
				}
			}
			
			
		}
		xhr.open("POST", 'fill_tag.php', true);
		xhr.responseType = 'json';
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.send( JSON.stringify(tag_list_el) ); 
	} else {
		console.error("fillCall element empty")
	}
}

var relatedCall = function ()
{
	let xhr = new XMLHttpRequest();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4) {
			status = xhr.status;
			if (status == 200) {
				if(xhr.response) {
					document.getElementById('tot').innerHTML = xhr.response.length + "/" 
					document.getElementById('tot_parz').innerHTML = 0
					var base_timeout = 0
					xhr.response.forEach(
						function(el){
							base_timeout = base_timeout + 500
							setTimeout(function(){ fillCall(el); }, base_timeout)
						}
					)
				}
			} else console.error("relatedCall status: "+ status)
		}
    }
	xhr.responseType = 'json';
	xhr.open("GET", 'related.php?tag_name=' + document.getElementById('hastag').value);
	xhr.send( null );
	
}

function copyToClip() {
  var copyText = document.getElementById("tags-list");
  copyText.select();
  document.execCommand("copy");
}

function addText(event) {
	if (parseFloat(document.getElementById("tag-counter").innerHTML) <= 29){
		var el = document.getElementById("id-"+event.replace("#",""));
		if (!el.classList.contains('table-primary')){
			document.getElementById("tags-list").value += event + " ";
			el.classList.add("table-primary");
			document.getElementById("tag-counter").innerHTML = parseFloat(document.getElementById("tag-counter").innerHTML)+ 1
		} else {
			document.getElementById("tags-list").value = document.getElementById("tags-list").value.replace(event + " ", "")
			el.classList.remove("table-primary");
			document.getElementById("tag-counter").innerHTML = parseFloat(document.getElementById("tag-counter").innerHTML) - 1
		}
	}else alert("30 hashtag selected")
}

</script>
</body>
</html>
