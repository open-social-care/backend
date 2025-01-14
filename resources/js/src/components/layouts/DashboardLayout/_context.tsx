import { PropsWithChildren, createContext, useContext, useState } from "react";

// Define your context props
export interface DashboardLayoutContextProps {
  menuVisible: boolean;
  showMenu: () => void;
  hideMenu: () => void;
  sidebarVisible: boolean;
  showSidebar: () => void;
  hideSidebar: () => void;
}

// Create a new context
export const DashboardLayoutContext = createContext<DashboardLayoutContextProps | undefined>(
  undefined,
);

// Create a custom context provider, so all context data will be self-contained
export default function DashboardLayoutContextProvider({ children }: PropsWithChildren) {
  const [menuVisible, setMenuVisible] = useState(false);
  const [sidebarVisible, setSidebarVisible] = useState(false);

  const context: DashboardLayoutContextProps = {
    menuVisible,
    showMenu: () => setMenuVisible(true),
    hideMenu: () => setMenuVisible(false),
    sidebarVisible,
    showSidebar: () => setSidebarVisible(true),
    hideSidebar: () => setSidebarVisible(false),
  };

  return (
    <DashboardLayoutContext.Provider value={context}>{children}</DashboardLayoutContext.Provider>
  );
}

//---------------------------------------- define a hook
export function useDashboardLayoutContext() {
  const context = useContext(DashboardLayoutContext);

  if (context === undefined) {
    throw new Error(
      "useDashboardLayoutContext must be used inside DashboardLayoutContextProvider!",
    );
  }

  return context;
}
