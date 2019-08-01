import { Application } from 'src/core/shopware';
import deDeSnippets from './snippet/de-DE.json';
import enGBSnippets from './snippet/en-GB.json';

Application.addInitializerDecorator('locale', (localeFactory) => {
    localeFactory.extend('de-DE', deDeSnippets);
    localeFactory.extend('en-GB', enGBSnippets);

    return localeFactory;
});
