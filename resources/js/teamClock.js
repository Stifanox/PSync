const state ={
    currentClockState: '',
    clockId: '',
    teamId:window.location.href.split('/')[window.location.href.split('/').length -1],
    endWorkTime: '',
    endFreeTime: '',
    clockElement:document.querySelector("#clock"),
    clockCountdownId: null,
    poolingId: null
}


function assignClock(){
    const timeToUse = state.currentClockState === "WORK" ? state.endWorkTime : state.endFreeTime
    let currentTime = (new Date(timeToUse).getTime()) - Date.now()
    currentTime = currentTime < 0 ? 0 : Math.floor( currentTime / 1000)


    let hours = Math.floor(currentTime / 3600);
    let minutes = Math.floor((currentTime % 3600) / 60);
    let seconds = currentTime % 60;

    const formatedTime = `${String(hours).padStart(2,"0")}:${String(minutes).padStart(2,"0")}:${String(seconds).padStart(2,"0")}`

    state.clockElement.innerText = formatedTime

    if(currentTime === 0){
        notifyServerAboutStateChange()
    }
}

function notifyServerAboutStateChange(){
    clearInterval(state.clockCountdownId)
    clearInterval(state.poolingId)
    fetch(`/teams/${state.teamId}/clocks/${state.clockId}/change-state`,{method:'POST'})
        .then(res=> res.json())
        .then(res => {
            if(res.data.clockState === 'ENDED'){
                fetchCurrentClock()
                setTimeout(notifyServerAboutStateChange, 1000)
            }
            else{
                state.currentClockState = res.data.clockState
                assignClock()
                createCountdown()
                createPooling()
            }
        })
}

// Fetch current clock data
function fetchCurrentClock(){
    fetch(`/teams/${state.teamId}/clocks`)
        .then(res => res.json())
        .then(res => {
            state.currentClockState = res.data.clockState
            state.clockId = res.data.id
            state.endWorkTime = res.data.endWorkTime
            state.endFreeTime = res.data.endFreeTime
            if(res.data.clockState !== 'ENDED'){
                updateClockResetText('')
                updateClockStateText()
                assignClock()
                createCountdown()
                createPooling()
            }
        })
        .catch(err =>{

        })
}


// Create clock loop
function createCountdown(){
    state.clockCountdownId = setInterval(()=>{
        assignClock()
    }, 500)
}

// Create notification pooling
function createPooling(){
    state.poolingId = setTimeout(()=>{
        fetch(`/teams/${state.teamId}/clocks/${state.clockId}`)
            .then(res => res.json())
            .then(res =>{
                if(res.data.clockState === "ENDED"){
                    fetchCurrentClock()
                    clearInterval(state.clockCountdownId)
                    clearInterval(state.poolingId)
                    return
                }
                state.currentClockState = res.data.clockState
                state.endWorkTime = res.data.endWorkTime
                state.endFreeTime = res.data.endFreeTime
                updateClockStateText()
                createPooling()
            })
    },2000)
}

function updateClockStateText(){
    document.querySelector('#clock-state').innerText = `Current: ${state.currentClockState.replace("_", " ")}`
}

function updateClockResetText(text){
    document.querySelector('#clock-reset').innerText = text
}

fetchCurrentClock()


document.querySelector('.reset-button').addEventListener('click', function (){
    updateClockResetText('The clock is resetting, it might take a few seconds')
    fetch(`/teams/${state.teamId}/clocks/${state.clockId}/reset-clock`, {method:"POST"})
})

