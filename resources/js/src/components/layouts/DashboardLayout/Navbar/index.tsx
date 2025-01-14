import auth from "@/auth";
import Logo from "./Logo";
import ProfileMenu from "./ProfileMenu";
import Root from "./Root";
import SidebarToggle from "./SidebarToggle";

type NavbarProps = {
  withoutSidebarToggle?: boolean;
};

export default async function Navbar({ withoutSidebarToggle }: NavbarProps) {
  const { user } = await auth();

  return (
    <Root>
      <Logo href="#" />

      <div>
        <ProfileMenu user={user} />

        {!withoutSidebarToggle && <SidebarToggle />}
      </div>
    </Root>
  );
}
