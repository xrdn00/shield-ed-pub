const sideLinks = document.querySelectorAll('.sidebar .side-menu li a:not(.logout)');

sideLinks.forEach(item => {
    const li = item.parentElement;
    item.addEventListener('click', () => {
        sideLinks.forEach(i => {
            i.parentElement.classList.remove('active');
        })
        li.classList.add('active');
    })
});

const menuBar = document.querySelector('.content nav .bx.bx-menu');
const sideBar = document.querySelector('.sidebar');

menuBar.addEventListener('click', () => {
    sideBar.classList.toggle('close');
});

const searchBtn = document.querySelector('.content nav form .form-input button');
const searchBtnIcon = document.querySelector('.content nav form .form-input button .bx');
const searchForm = document.querySelector('.content nav form');



window.addEventListener('resize', () => {
    if (window.innerWidth < 768) {
        sideBar.classList.add('close');
    } else {
        sideBar.classList.remove('close');
    }

});

const toggler = document.getElementById('theme-toggle');

// Check for saved 'darkMode' in localStorage
if (localStorage.getItem('darkMode') === 'true') {
    document.body.classList.add('dark');
    toggler.checked = true;
}

toggler.addEventListener('change', function () {
    if (this.checked) {
        document.body.classList.add('dark');
        // Save the preference to localStorage
        localStorage.setItem('darkMode', 'true');
    } else {
        document.body.classList.remove('dark');
        // Save the preference to localStorage
        localStorage.setItem('darkMode', 'false');
    }
});



//PEERJS START:

//db data
const user_id = [];
const user_firstname = [];
const user_lastname = [];
const user_email = [];
const category = [];
const user_time = [];

let pair = [];

//calldata
const participants = [];
const caller = [];
const callee = [];
const startTime = [];
const endTime = [];

//date


var year = null;
var month = null; 
var day = null;

var hours = null;
var minutes = null;
var seconds = null;
var datetimeFormat = null;

function time(){
  const date = new Date();
  let yy = date.getFullYear();
  let mm = date.getMonth();
  let dd = date.getDate();

  let h = date.getHours();
  let m = date.getMinutes();
  let s = date.getSeconds();

  year = yy;
  month = mm+1;
  day = dd;

  // Convert to 12-hour format
  let period = h >= 12 ? 'PM' : 'AM';
  hours = h % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = m;
  seconds = s;

  let dtf =`${month}-${day}-${year} ${hours}:${minutes}:${seconds} ${period}`;
  datetimeFormat = dtf;
}
const clock = setInterval(time,1000);


//listentocall must be start first before makecall
let count = 1;
var peer = new Peer()
var myStream
var peerList = []
const myid = []
const connectedUsers = new Set();

const callResponse = new Set();

let formSubmitCount = 0;

function callres() {
  console.log(Array.from(callResponse));
}



//this function will be initiating the peer
function init(userId) {
  peer = new Peer(userId)
  peer.on('open', (id) => {
    console.log(id + " connected") //if we connect successfully this will print
    myid.push(id);
    

  })






  listenToCall();
  // Request push notification permission
  Notification.requestPermission().then((permission) => {
    if (permission === 'granted') {
      subscribeToPushNotifications();
    }
  });


}

// Function to subscribe to push notifications
function subscribeToPushNotifications() {
  navigator.serviceWorker.ready.then((registration) => {
    return registration.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: '', // Replace with your actual public key
    });
  }).then((subscription) => {
    // Send the subscription details to your server
    // Your server will use this to send push notifications
    console.log('Subscription:', subscription);
  }).catch((error) => {
    console.error('Error subscribing to push notifications:', error);
  });
}

// Function to trigger a push notification
function triggerPushNotification(title, body) {
  navigator.serviceWorker.ready.then((registration) => {
    registration.showNotification(title, { body: body });
  });
}


//--------------------------------------------------------------
function sendMessage() {
  const targetUserId = document.getElementById("studentid").value;

  const message = "admin is calling";

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });
}
function StudentsendMessage() {
  const targetUserId = document.getElementById("adminid").value;

  const message = "student is calling";

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);
  });
}
function callDeclined() {
  const targetUserId = Array.from(connectedUsers).toString();

  const message = "Call Declined";

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });

}
function callAccepted() {
  const targetUserId = Array.from(connectedUsers).toString();

  const message = "call accepted";

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });

}

function callhangup() {
  const targetUserId = Array.from(connectedUsers).toString();

  const message = `${myid.toString()} hanged up the call...`;

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });

}

function callstarted(){
  const targetUserId = Array.from(connectedUsers).toString();

  const message = `start:${startTime}`;

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });

}

function userInfo(){
  const targetUserId = Array.from(connectedUsers).toString();
  const data = userSendInfo().toString();
  const message = `user info:${data}`;

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });

}
function adminAskInfo(){
  const targetUserId = Array.from(connectedUsers).toString();
  const message = "need info";

  const connection = peer.connect(targetUserId, { reliable: true });

  connection.on('open', () => {
    connection.send(message);
    displayMessage(`Sent to ${targetUserId}: ${message}`);

  });
}




function displayMessage(message) {
  
  return message.toString();

}

function listConnectedUsers() {
  console.log('Connected users:', Array.from(connectedUsers));
}



//------------------------------------------------------------
//this function will keep listening to call or incoming events
function listenToCall() {
  peer.on('call', (call) => {
    openCallModal(call);


  });
  peer.on('open', (call) => {
    console.log(`Your user ID is: ${call}`);
  });

  peer.on('connection', (connection) => {
    connectedUsers.add(connection.peer);


    connection.on('data', (data) => {
      
      displayMessage(`Received from ${connection.peer}: ${data}`);
      let result = displayMessage(`Received from ${connection.peer}: ${data}`);
      const vcallmodal = document.getElementById("vcallmodal");
      const userDecline = document.getElementById("userDecline");
      const okbutton = document.getElementById("okbutton");

      if (result.includes("call accepted")) {

        pair.push(myid);
        pair.push(peerList);
        participants.push(pair.toString());
        caller.push(pair[0]);
        callee.push(pair[1]);
        console.log("adding call data info...");
        




      }

      else if (result.includes("Call Declined")) {
        
        vcallmodal.style.display = "none";
        count += 10;
        userDecline.style.display = "block";


        okbutton.onclick = () => {
          location.reload();
        }

      }
      else if (result.includes("hanged up")) {
        
        endTime.push(datetimeFormat);
        document.getElementById("endTime").value = endTime.toString();
        
     

        ////////////
        count += 10;

      }
      else if(result.includes("start")){
        const time = result.split("start:");
        startTime.push(time[1]);

        console.log(startTime);
      }
      else if(result.includes("user info")){
        const new_info = result.split(":");
        console.log(result);
        console.log(new_info);
        const data = new_info[2];

        const parts = data.split(",");
        user_id.push(parts[0]);
        user_firstname.push(parts[1]);
        user_lastname.push(parts[2]);
        user_email.push(parts[3]);
        category.push(parts[4]);
        user_time.push(parts[5]);



      }
      else if(result.includes("need info")){
        category.push("admin called.");
        document.getElementById("category").value = category;
        userInfo();
      }





    });

    connection.on('open', () => {
      console.log(`Connection with ${connection.peer} opened.`);
      

    });

    connection.on('close', () => {
      connectedUsers.delete(connection.peer);
      console.log(`Connection with ${connection.peer} closed.`);
      document.getElementById("endTime").value = datetimeFormat;
      console.log("Caller Hang up...");
      endTime.push(datetimeFormat);
      document.getElementById("endTime").value = endTime.toString();
      formSubmitCount++;
      if(formSubmitCount==1){
        submitForm();
      }

      
      
      

      
      
      
      

      /////////////

    });
  });

}
function openCallModal(call) {
  // custom modal in your HTML and style it with CSS
  const modal = document.getElementById("customModal");
  const acceptButton = document.getElementById("acceptButton");
  const declineButton = document.getElementById("declineButton");
  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");


  triggerPushNotification("Incoming video call", "Click to answer");



  // Set the call information in the modal
  const callInfo = document.getElementById("callInfo");
  callInfo.textContent = `Incoming call from ${call.peer}. Do you want to answer?`;

  // Show the modal
  modal.style.display = "block";


  // Handle the accept button click
  acceptButton.onclick = () => {
    
    modal.style.display = "none";
    vcallmodal.style.display = "block";


    navigator.mediaDevices.getUserMedia({

      video: true,
      audio: true
    }).then((stream) => {
      myStream = stream;
      addLocalVideo(stream);
      call.answer(stream);
      call.on('stream', (remoteStream) => {
        if (!peerList.includes(call.peer)) {
          addRemoteVideo(remoteStream);
          peerList.push(call.peer);

          callAccepted();
          //initialize form
          pair.push(peerList);
          pair.push(myid);
          
          participants.push(pair.toString());
          caller.push(pair[0]);
          callee.push(pair[1]);
          startTime.push(datetimeFormat);
          callstarted();
          

          
          hangupbtn.onclick = () => {
            callhangup();
            location.reload();
          }
        }
      });
    }).catch((err) => {
      console.log("Unable to connect because " + err);
    });
  };

  // Handle the decline button click
  declineButton.onclick = () => {

    modal.style.display = "none";
    call.close(); // Decline the call
    callDeclined();
    


  };

}

//this function will be called when we try to make a call
function makeCall(receiverId) {
  navigator.mediaDevices.getUserMedia({
    video: true,
    audio: true
  }).then((stream) => {
    myStream = stream
    addLocalVideo(stream)
    let call = peer.call(receiverId, stream)
    
    // Create a data channel


    // Set up data channel event listeners



    call.on('stream', (remoteStream) => {
      if (!peerList.includes(call.peer)) {
        
        addRemoteVideo(remoteStream)
        peerList.push(call.peer)
      }
    })
  }).catch((err) => {
    console.log("unable to connect because " + err)
  })

}
let vcount = 0;
//this function will add local stream to video pannel
function addLocalVideo(stream) {
  let video = document.createElement("video")
  video.srcObject = stream
  video.classList.add("video")
  video.muted = true // local video need to be mute because of noise issue
  video.play()
  vcount++;
  if (vcount == 1) {
    document.getElementById("localVideo").append(video)
  }
  else {
    console.log("calling...")
    
  }

}

//this function will add remote stream to video pannel
function addRemoteVideo(stream) {
  let video = document.createElement("video")
  video.srcObject = stream
  video.classList.add("video")
  video.play()
  document.getElementById("remoteVideo").append(video)
}



/*
function toggleVideo(b) {
  if (b == "true") {
    myStream.getVideoTracks()[0].enabled = true
  }
  else {
    myStream.getVideoTracks()[0].enabled = false
  }
}

//toggle audio
function toggleAudio(b) {
  if (b == "true") {
    myStream.getAudioTracks()[0].enabled = true
  }
  else {
    myStream.getAudioTracks()[0].enabled = false
  }
}
*/



function admincallmodal() {
  const admincall = document.getElementById("admincall");
  const studentid = document.getElementById("studentid");
  const callbutton = document.getElementById("callbutton");
  const cancelbutton = document.getElementById("cancelbutton");

  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");
  admincall.style.display = "block";

  //spams call notif
  callbutton.onclick = () => {
    admincall.style.display = "none";
    vcallmodal.style.display = "block";

    

    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        
        makeCall(studentid.value);
        sendMessage();
        
        

      }
      if (peerList.length > 0) {
        clearInterval(intervalId);
        adminAskInfo();
      }




    }, 5000);



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    admincall.style.display = "none";
  }


}


function studentcall1() {

  category.pop()
  category.push("Construction Hazard")
  document.getElementById("category").value = "Construction Hazard";

  const studentcallmodal = document.getElementById("studentcallmodal");
  const adminid = document.getElementById("adminid");
  const callbutton = document.getElementById("callbutton");
  const cancelbutton = document.getElementById("cancelbutton");

  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");
  studentcallmodal.style.display = "block";

  //spams call notif
  callbutton.onclick = () => {
    studentcallmodal.style.display = "none";
    vcallmodal.style.display = "block";


    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        makeCall(adminid.value);
        StudentsendMessage();

      }
      if (peerList.length > 0) {
        userInfo();
        clearInterval(intervalId);
      }



    }, 5000);



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    studentcallmodal.style.display = "none";
  }


}
function studentcall2() {
  category.pop()
  category.push("Violence")
  document.getElementById("category").value = "Violence";

  const studentcallmodal = document.getElementById("studentcallmodal");
  const adminid = document.getElementById("adminid");
  const callbutton = document.getElementById("callbutton");
  const cancelbutton = document.getElementById("cancelbutton");

  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");
  studentcallmodal.style.display = "block";

  //spams call notif
  callbutton.onclick = () => {
    studentcallmodal.style.display = "none";
    vcallmodal.style.display = "block";


    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        makeCall(adminid.value);
        StudentsendMessage();

      }
      if (peerList.length > 0) {
        userInfo();
        clearInterval(intervalId);
        
      }



    }, 5000);



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    studentcallmodal.style.display = "none";
  }


}
function studentcall3() {
  category.pop()
  category.push("Medical Attention")
  document.getElementById("category").value = "Medical Attention";

  const studentcallmodal = document.getElementById("studentcallmodal");
  const adminid = document.getElementById("adminid");
  const callbutton = document.getElementById("callbutton");
  const cancelbutton = document.getElementById("cancelbutton");

  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");
  studentcallmodal.style.display = "block";

  //spams call notif
  callbutton.onclick = () => {
    studentcallmodal.style.display = "none";
    vcallmodal.style.display = "block";


    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        makeCall(adminid.value);
        StudentsendMessage();

      }
      if (peerList.length > 0) {
        userInfo();
        clearInterval(intervalId);
      }



    }, 5000);
    



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    studentcallmodal.style.display = "none";
  }


}
function studentcall4() {
  category.pop()
  category.push("Fire/Explosion")
  document.getElementById("category").value = "Fire/Explosion";

  const studentcallmodal = document.getElementById("studentcallmodal");
  const adminid = document.getElementById("adminid");
  const callbutton = document.getElementById("callbutton");
  const cancelbutton = document.getElementById("cancelbutton");

  const vcallmodal = document.getElementById("vcallmodal");
  const hangupbtn = document.getElementById("hangupbtn");
  studentcallmodal.style.display = "block";

  //spams call notif
  callbutton.onclick = () => {
    studentcallmodal.style.display = "none";
    vcallmodal.style.display = "block";


    const intervalId = setInterval(() => {


      count++;
      if (count > 10) {
        clearInterval(intervalId);
        peerList.push("end");
      }
      if (peerList.length == 0) {
        makeCall(adminid.value);
        StudentsendMessage();

      }
      if (peerList.length > 0) {
        userInfo();
        clearInterval(intervalId);
      }



    }, 5000);



  }
  hangupbtn.onclick = () => {
    location.reload();
  }
  cancelbutton.onclick = () => {
    studentcallmodal.style.display = "none";
  }


}


function userSendInfo(){

  user_id.push(document.getElementById("user_id").value),
  user_firstname.push(document.getElementById("user_firstname").value),
  user_lastname.push(document.getElementById("user_lastname").value),
  user_email.push(document.getElementById("user_email").value),
  category.pop();
  category.push(document.getElementById("category").value),

  user_time.push(datetimeFormat)


  const info = `${user_id},${user_firstname},${user_lastname},${user_email},${category},${user_time.toString()}`;
  return info;
}




function submitForm() {
  document.getElementById("participants").value = participants.toString();
  document.getElementById("caller").value = caller.toString();
  document.getElementById("callee").value = callee.toString();
  document.getElementById("startTime").value = startTime.toString();
  document.getElementById("endTime").value = endTime.toString();

  document.getElementById("user_id").value = user_id.toString();
  document.getElementById("user_firstname").value = user_firstname.toString();
  document.getElementById("user_lastname").value = user_lastname.toString();
  document.getElementById("user_email").value = user_email.toString();
  document.getElementById("category").value = category.toString();
  document.getElementById("user_time").value = datetimeFormat.toString();
  
  document.getElementById("calldata").submit();
}





async function callnotif(){

  //where id is studentid
  console.log("CLICK");
  fetch('https://express-server-vnhn.onrender.com/sendusernotif', {
     method: 'POST',
     headers: {
         'Content-Type': 'application/json',
     },
     body: JSON.stringify({token: studentid})
  })
  .then(response => response.json())
  .then(data => console.log(data))
  .catch((error) => {
     console.error('Error:', error);
  });

  sendMessage();
}

//implement camera switch front/back




