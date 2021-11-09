<template>
    <div class="container">
        <div id="interactive" class="viewport">
            <qrcode-stream :key="_uid" :track="selected.value" @init="logErrors"/>
            <qrcode-capture ></qrcode-capture>
        </div>
    </div>
</template>


<script>
import {QrcodeStream} from "vue-qrcode-reader";
export default {

  components: { QrcodeStream },

  data () {
    const options = [
      { text: "nothing (default)", value: undefined },
      { text: "outline", value: this.paintOutline },
      { text: "centered text", value: this.paintCenterText },
      { text: "bounding box", value: this.paintBoundingBox },
    ]

    const selected = options[1]

    return { selected, options }
  },

  methods: {
    onDecode(decodedString){
        var context = "event"
        switch(decodedString.length){
            case 4:
                context = "team"
                break;
            case 13:
                context = "user"
                break;
            default:
                break;
        }
        const requestOptions = {
            method: "POST",
            headers:{
                "Content-Type":"application/json",
                "X-CSRF-TOKEN": window.Laravel.csrfToken,
            },
            body: JSON.stringify({
                code: decodedString,
                context: context
            })
        }
        fetch('/api/scanned',requestOptions)
    },
    paintOutline (detectedCodes, ctx) {
      for (const detectedCode of detectedCodes) {
        const [ firstPoint, ...otherPoints ] = detectedCode.cornerPoints

        ctx.strokeStyle = "red";

        ctx.beginPath();
        ctx.moveTo(firstPoint.x, firstPoint.y);
        for (const { x, y } of otherPoints) {
          ctx.lineTo(x, y);
        }
        ctx.lineTo(firstPoint.x, firstPoint.y);
        ctx.closePath();
        ctx.stroke();
      }
    },

    paintBoundingBox (detectedCodes, ctx) {
      for (const detectedCode of detectedCodes) {
        const { boundingBox: { x, y, width, height } } = detectedCode

        ctx.lineWidth = 2
        ctx.strokeStyle = '#007bff'
        ctx.strokeRect(x, y, width, height)
      }
    },

    paintCenterText (detectedCodes, ctx) {
      for (const detectedCode of detectedCodes) {
        const { boundingBox, rawValue } = detectedCode

        const centerX = boundingBox.x + boundingBox.width/ 2
        const centerY = boundingBox.y + boundingBox.height/ 2

        const fontSize = Math.max(12, 50 * boundingBox.width/ctx.canvas.width)
        console.log(boundingBox.width, ctx.canvas.width)

        ctx.font = `bold ${fontSize}px sans-serif`
        ctx.textAlign = "center"

        ctx.lineWidth = 3
        ctx.strokeStyle = '#35495e'
        ctx.strokeText(detectedCode.rawValue, centerX, centerY)

        ctx.fillStyle = '#5cb984'
        ctx.fillText(rawValue, centerX, centerY)
      }
    },

    logErrors (promise) {
      promise.catch(console.error)
    }
  }

}
</script>
