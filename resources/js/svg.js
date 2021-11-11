
    const icons = {}
    const defaultClass = ""

    module.exports = (name, attributes = '') => {
      var replacement = "<svg ";

      if (!icons[name]) {
        console.error('Failed to load SVG ' + name);
        return;
      }

      if (typeof attributes === "object") {
          for (let property in attributes) {
              if (attributes.hasOwnProperty(property)) {
                  let value = typeof attributes[property] == 'string'
                      ? attributes[property]
                      : JSON.stringify(attributes[property]);

                  replacement += property + "='" + value + "' ";
              }
          }
      } else {
          replacement += 'class="' + defaultClass + " " + attributes + '" ';
      }

      return icons[name].replace('<svg ', replacement);
    };
  