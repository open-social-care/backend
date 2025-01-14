type SideBarLinkGroupProps = {
  title: string;
  icon: React.ReactNode;
} & React.ComponentPropsWithoutRef<"div">;

export default function SideBarLinkGroup({
  children,
  title,
  icon,
  ...rest
}: SideBarLinkGroupProps) {
  return (
    <div {...rest}>
      <div className="rounded-m flex transform items-center px-3 py-2 uppercase text-teal-900 transition-colors duration-300">
        <span className="h-5 w-5">{icon}</span>

        <span className="text-md mx-2 truncate font-medium">{title}</span>
      </div>

      {children}
    </div>
  );
}
