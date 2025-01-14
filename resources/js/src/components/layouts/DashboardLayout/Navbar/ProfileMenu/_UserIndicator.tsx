import { User } from "@/schemas";

type UserIndicatorProps = {
  user: User;
};

export default function UserIndicator({ user }: UserIndicatorProps) {
  return (
    <div className="mx-1 text-start">
      <h1 className="text-sm font-semibold">{user.name}</h1>
      <p className="text-sm">{user.email}</p>
    </div>
  );
}
