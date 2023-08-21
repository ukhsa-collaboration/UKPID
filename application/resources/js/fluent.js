import {
  baseLayerLuminance,
  fillColor,
  fluentButton,
  fluentCard,
  neutralLayer1,
  // neutralLayer2,
  provideFluentDesignSystem,
  StandardLuminance,
  typeRampBaseFontSize,
} from "@fluentui/web-components";

provideFluentDesignSystem().register(fluentButton(), fluentCard());

const app = document.getElementById("ukpid");

typeRampBaseFontSize.setValueFor(app, "1rem");

fillColor.setValueFor(app, neutralLayer1);
baseLayerLuminance.setValueFor(app, StandardLuminance.LightMode);

// const layer2 = document.getElementById('layer-2');
//
// fillColor.setValueFor(layer2, neutralLayer2);
