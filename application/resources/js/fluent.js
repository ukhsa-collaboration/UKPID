import {
  baseLayerLuminance,
  designUnit,
  fillColor,
  fluentButton,
  fluentCard,
  fluentTextField,
  neutralLayer1,
  // neutralLayer2,
  provideFluentDesignSystem,
  StandardLuminance,
  typeRampBaseFontSize,
  typeRampBaseLineHeight,
  typeRampMinus1FontSize,
  typeRampMinus1LineHeight,
  typeRampMinus2FontSize,
  typeRampMinus2LineHeight,
  typeRampPlus1FontSize,
  typeRampPlus1LineHeight,
  typeRampPlus2FontSize,
  typeRampPlus2LineHeight,
  typeRampPlus3FontSize,
  typeRampPlus3LineHeight,
  typeRampPlus4FontSize,
  typeRampPlus4LineHeight,
  typeRampPlus5FontSize,
  typeRampPlus5LineHeight,
  typeRampPlus6FontSize,
  typeRampPlus6LineHeight,
} from "@fluentui/web-components";

provideFluentDesignSystem().register(
  fluentButton(),
  fluentCard(),
  fluentTextField(),
);

/**
 * App styles
 */
const root = document.getRootNode();

typeRampMinus2FontSize.setValueFor(root, "0.625rem"); // 10px
typeRampMinus1FontSize.setValueFor(root, "1rem"); // 12px
typeRampBaseFontSize.setValueFor(root, "0.85rem"); // 14px
typeRampPlus1FontSize.setValueFor(root, "1rem"); // 16px
typeRampPlus2FontSize.setValueFor(root, "1.25rem"); // 20px
typeRampPlus3FontSize.setValueFor(root, "1.5rem"); // 24px
typeRampPlus4FontSize.setValueFor(root, "1.75rem"); // 28px
typeRampPlus5FontSize.setValueFor(root, "2rem"); // 32px
typeRampPlus6FontSize.setValueFor(root, "2.5rem"); // 40px

typeRampMinus2LineHeight.setValueFor(root, "0.875rem"); // 14px
typeRampMinus1LineHeight.setValueFor(root, "1rem"); // 16px
typeRampBaseLineHeight.setValueFor(root, "1.25rem"); // 20px
typeRampPlus1LineHeight.setValueFor(root, "1.375rem"); // 22px
typeRampPlus2LineHeight.setValueFor(root, "1.625rem"); // 26px
typeRampPlus3LineHeight.setValueFor(root, "2rem"); // 32px
typeRampPlus4LineHeight.setValueFor(root, "2.25rem"); // 36px
typeRampPlus5LineHeight.setValueFor(root, "2.5rem"); // 40px
typeRampPlus6LineHeight.setValueFor(root, "3.25rem"); // 52px

fillColor.setValueFor(root, neutralLayer1);
baseLayerLuminance.setValueFor(root, StandardLuminance.LightMode);

/**
 * Inputs
 */

const largeInputs = document.querySelectorAll(".input-large");

largeInputs.forEach((el) => {
  designUnit.setValueFor(el, 5);
  typeRampBaseFontSize.setValueFor(el, typeRampPlus1FontSize);
  typeRampBaseLineHeight.setValueFor(el, typeRampPlus1LineHeight);
});

// const layer2 = document.getElementById('layer-2');
//
// fillColor.setValueFor(layer2, neutralLayer2);
