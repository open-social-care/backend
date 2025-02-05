"use client";

import { AiOutlineMenu } from "react-icons/ai";
import { IoMdClose } from "react-icons/io";

import { useDashboardLayoutContext } from "../_context";

export default function SidebarToggle() {
  const { showSidebar, hideSidebar, sidebarVisible } = useDashboardLayoutContext();

  return (
    <button
      className="inline-block md:hidden"
      onClick={() => {
        sidebarVisible ? hideSidebar() : showSidebar();
      }}
    >
      <div className="mx-1 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full object-cover">
        {sidebarVisible ? <IoMdClose size={22} /> : <AiOutlineMenu size={22} />}
      </div>
    </button>
  );
}
