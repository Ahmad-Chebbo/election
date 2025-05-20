export type Team = {
    id: number;
    name: string;
    description: string;
    color: string;
};

export type Candidate = {
    id: number;
    name: string;
    team_id: number;
};