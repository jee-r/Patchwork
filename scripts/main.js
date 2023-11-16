function submitForm(event) {
    event.preventDefault(); // Prevent the form from submitting in the traditional way
    const resultContainer = document.getElementById('resultcontainer')
    const usernameField = document.getElementById('username')
    const submitbtn = document.getElementById('submitbtn')
    const patchworkStaticLink = document.getElementById('patchworkStaticLink')
    const patchworkDynLink = document.getElementById('patchworkDynLink')
    const patchworkImg = document.getElementById('patchworkImg')
    submitbtn.setAttribute("aria-busy", "true")
    submitbtn.innerHTML = "Generating Patchwork, please wait…"
    resultContainer.classList.add('hidden')
    // resultContainer.innerHTML = '<div aria-busy="true">Generating Patchwork, please wait…</div>'
    // Fetch the form data
    const formData = new FormData(event.target);
    const patchworkDynamicParams = new URLSearchParams(formData).toString(); 

    // add json true to url param to get json data instead of image
    formData.append('json', 'true');
    // Make an asynchronous request to patchwork.php
    fetch(event.target.action + "?" + new URLSearchParams(formData), {
        method: 'GET',
    })
    .then(response => response.json()) // Assuming patchwork.php returns text
    .then(result => {
       
        if (result.error) {
            submitbtn.removeAttribute("aria-busy")
            submitbtn.innerHTML = result.error  
            return
        }

        submitbtn.removeAttribute("aria-busy")
        submitbtn.innerHTML = "Patchwork Generated !"
        
        const baseUrl = location.protocol.concat("//").concat(window.location.host);
        const patchworkDynamicUrl = baseUrl + "/patchwork.php?" + patchworkDynamicParams
        const patchworkStaticUrl = baseUrl + "/" + result.imagePath;
        
        // Update the result container with the response
        patchworkStaticLink.innerHTML = patchworkStaticUrl;
        patchworkStaticLink.setAttribute("href", patchworkStaticUrl );
        patchworkDynLink.innerHTML = patchworkDynamicUrl;
        patchworkDynLink.setAttribute("href", patchworkDynamicUrl);
        
        patchworkImg.setAttribute("src", patchworkStaticUrl)
        patchworkImg.setAttribute("height", result.height)
        patchworkImg.setAttribute("width", result.width)
        
        resultContainer.classList.remove('hidden')
    })
    .catch(error => console.error('Error:', error));
}

function copyToClipboard(event, elementId) {
    
    const clickedElement = event.currentTarget;
    const elementToCopy = document.getElementById(elementId);

    // Select the text field
    const copyText = elementToCopy.innerHTML;
    console.log('copyText', copyText)
    // copyText.setSelectionRange(0, 99999); // For mobile devices
    
    // Copy the text inside the text field
    navigator.clipboard.writeText(copyText);
    
    // Alert the copied text
    // alert("Copied the text: " + copyText);
    
    clickedElement.innerHTML = "Copied !!"
    clickedElement.classList.add('secondary');
    clickedElement.classList.remove('contrast');
    setTimeout(() => {
        clickedElement.innerHTML = "Copy"
        clickedElement.classList.add('contrast');
        clickedElement.classList.remove('secondary');
    }, 3000);

}