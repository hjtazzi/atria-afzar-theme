import React from "react";

const ThemeStateContext = React.createContext();
const ThemeDispatchContext = React.createContext();

const ThemeReducer = (state, action) => {
    switch (action.type) {
        case "SET_LOADING":
            return { ...state, loading: action.payload };
            case "SET_ITEM_MENU":
                return { ...state, itemMenu: action.payload };
        default: {
            throw new Error(`Unhandled action type: ${action.type}`);
        }
    }
};

const ThemeProvider = ({ children, payloads }) => {
    const [state, dispatch] = React.useReducer(ThemeReducer, payloads);

    return (
        <ThemeStateContext.Provider value={state}>
            <ThemeDispatchContext.Provider value={dispatch}>
                {children}
            </ThemeDispatchContext.Provider>
        </ThemeStateContext.Provider>
    );
};

const useThemeState = () => {
    const context = React.useContext(ThemeStateContext);
    if (context === undefined) {
        throw new Error("useThemeState must be used within a ThemeProvider");
    }
    return context;
};

const useThemeDispatch = () => {
    const context = React.useContext(ThemeDispatchContext);
    if (context === undefined) {
        throw new Error("useThemeDispatch must be used within a ThemeProvider");
    }
    return context;
};

export { ThemeProvider, useThemeState, useThemeDispatch };