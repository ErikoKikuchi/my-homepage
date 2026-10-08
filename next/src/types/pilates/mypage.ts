export type MypageData = {
  notLineLinkedClient: boolean;
  nextReservationInfo: { date: string; location: string | null } | null;
  remainingTicketCounts: number;
  upcomingReservations: {
    uuid: string;
    date: string;
    location: string | null;
  }[];
};
