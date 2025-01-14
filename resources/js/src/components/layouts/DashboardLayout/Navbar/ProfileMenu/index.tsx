"use client";

import { User } from "@/schemas";

import { AiOutlineUser } from "react-icons/ai";
import { FaChevronDown } from "react-icons/fa";

import useOnclickOutside from "react-cool-onclickoutside";
import { twMerge } from "tailwind-merge";

import { t } from "@/lang";
import { useRef } from "react";
import { useDashboardLayoutContext } from "../../_context";
import MenuLink from "./_MenuLink";
import UserIndicator from "./_UserIndicator";

type ProfileProps = {
  user: User;
} & React.ComponentPropsWithoutRef<"div">;

export default function ProfileMenu({ children, className, user, ...rest }: ProfileProps) {
  const { showMenu, hideMenu, menuVisible, hideSidebar } = useDashboardLayoutContext();

  const buttonRef = useRef(null);
  const dropdownRef = useRef(null);

  useOnclickOutside(
    () => {
      hideMenu();
    },
    { refs: [buttonRef, dropdownRef] },
  );

  return (
    <div
      className={twMerge("relative inline-block", className)}
      {...rest}
    >
      <button
        onClick={() => {
          hideSidebar();
          menuVisible ? hideMenu() : showMenu();
        }}
        ref={buttonRef}
        className="relative z-10 flex items-center rounded-md border border-transparent bg-white p-2 text-sm  focus:border-teal-500 focus:outline-none focus:ring focus:ring-teal-300 focus:ring-opacity-40"
      >
        <div className="mx-1 flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full object-cover">
          <AiOutlineUser size={22} />
        </div>

        <div className="hidden sm:flex">
          <UserIndicator user={user} />

          <div className="flex h-9 w-9 items-center justify-center">
            <FaChevronDown size={12} />
          </div>
        </div>
      </button>

      <div
        className={twMerge(
          "absolute right-0 z-20 mt-2 w-56 origin-top-right overflow-hidden rounded-md bg-white py-2 shadow-xl",
          menuVisible ? "block" : "hidden",
        )}
        ref={dropdownRef}
      >
        <div className="-mt-2 flex transform flex-col px-4 py-2 sm:hidden">
          <UserIndicator user={user} />
          <hr className="mt-2 border-gray-200" />
        </div>

        <MenuLink
          href="/profile-select"
          title={t("labels.change_profile")}
        />

        {/* <MenuLink
          href="#"
          title="Ver Perfil"
        /> */}

        <MenuLink
          href="/login"
          title="Logout"
        />
      </div>
    </div>
  );
}
