import React from 'react';
import ReactDOM from 'react-dom';

import ErrorBoundary from './error/ErrorBoundary';
import { ThemeProvider } from './context/ThemeContext';
import App from './components/App';

const themeProviderPayloads = {
  site_url: localStorage.getItem("site_url"),
  loading: true,
  itemMenu: "dashboard",
};

ReactDOM.render(
  <React.StrictMode>
    <ErrorBoundary>
      <ThemeProvider payloads={themeProviderPayloads}>
        <App />
      </ThemeProvider>
    </ErrorBoundary>
  </React.StrictMode>,
  document.getElementById('app_root')
);
